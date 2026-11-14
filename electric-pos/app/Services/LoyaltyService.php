<?php
namespace App\Services;

class LoyaltyService
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Cron Job: Credit Pending Points (Runs after 24 hours of sale)
     * Finds pending lots where available_at <= NOW() and updates available balance
     */
    public function creditPendingPoints()
    {
        $now = date('Y-m-d H:i:s');
        $pendingLots = $this->db->table('tbl_loyalty_points_lot')
            ->where('status', 'pending')
            ->where('available_at <=', $now)
            ->get()->getResult();

        $creditedCount = 0;
        foreach ($pendingLots as $lot) {
            $this->db->transStart();

            // 1. Update lot status to available
            $this->db->table('tbl_loyalty_points_lot')
                ->where('id', $lot->id)
                ->update(['status' => 'available', 'updated_at' => $now]);

            // 2. Transfer from pending to available in account
            $this->db->table('tbl_loyalty_account')
                ->where('id', $lot->account_id)
                ->set('pending_points', 'GREATEST(0, pending_points - ' . (float)$lot->earned_points . ')', false)
                ->set('available_points', 'available_points + ' . (float)$lot->earned_points, false)
                ->set('lifetime_earned', 'lifetime_earned + ' . (float)$lot->earned_points, false)
                ->update();

            // 3. Log credit transaction
            $this->db->table('tbl_loyalty_transaction')->insert([
                'account_id' => $lot->account_id,
                'lot_id'     => $lot->id,
                'txn_type'   => 'earn',
                'points'     => $lot->earned_points,
                'ref_type'   => 'sale',
                'ref_id'     => $lot->sale_id,
                'note'       => '24-hour credit delay elapsed: Points now available for redemption',
            ]);

            $this->db->transComplete();
            if ($this->db->transStatus()) {
                $creditedCount++;
            }
        }

        return $creditedCount;
    }

    /**
     * Cron Job: Expire Points Lots (Runs daily)
     * Finds available lots where expires_at <= NOW() and remaining_pts > 0
     */
    public function expirePoints()
    {
        $now = date('Y-m-d H:i:s');
        $expiredLots = $this->db->table('tbl_loyalty_points_lot')
            ->where('status', 'available')
            ->where('expires_at <=', $now)
            ->where('remaining_pts >', 0)
            ->get()->getResult();

        $expiredCount = 0;
        foreach ($expiredLots as $lot) {
            $this->db->transStart();

            $expPoints = $lot->remaining_pts;

            // 1. Mark lot expired
            $this->db->table('tbl_loyalty_points_lot')
                ->where('id', $lot->id)
                ->update(['status' => 'expired', 'remaining_pts' => 0, 'updated_at' => $now]);

            // 2. Reduce available points in account
            $this->db->table('tbl_loyalty_account')
                ->where('id', $lot->account_id)
                ->set('available_points', 'GREATEST(0, available_points - ' . (float)$expPoints . ')', false)
                ->update();

            // 3. Record expiration log
            $this->db->table('tbl_loyalty_expiration')->insert([
                'account_id'  => $lot->account_id,
                'lot_id'      => $lot->id,
                'expired_pts' => $expPoints,
            ]);

            // 4. Log transaction
            $this->db->table('tbl_loyalty_transaction')->insert([
                'account_id' => $lot->account_id,
                'lot_id'     => $lot->id,
                'txn_type'   => 'expire',
                'points'     => -$expPoints,
                'note'       => 'Points expired after 2 months validity period',
            ]);

            $this->db->transComplete();
            if ($this->db->transStatus()) {
                $expiredCount++;
            }
        }

        return $expiredCount;
    }

    /**
     * Manual Point Adjustment with Audit Trail
     */
    public function adjustPoints($accountId, $points, $reason, $userId)
    {
        $this->db->transStart();

        $account = $this->db->table('tbl_loyalty_account')->where('id', $accountId)->get()->getRow();
        if (!$account) return false;

        $newBal = max(0, $account->available_points + $points);

        $this->db->table('tbl_loyalty_account')
            ->where('id', $accountId)
            ->update(['available_points' => $newBal, 'updated_at' => date('Y-m-d H:i:s')]);

        $this->db->table('tbl_loyalty_transaction')->insert([
            'account_id' => $accountId,
            'txn_type'   => 'adjust',
            'points'     => $points,
            'note'       => 'Manual Adjustment: ' . $reason,
            'created_by' => $userId,
        ]);

        // Audit Log
        $this->db->table('tbl_audit_log')->insert([
            'user_id'    => $userId,
            'store_id'   => $account->store_id,
            'module'     => 'loyalty',
            'action'     => 'loyalty.points.adjust',
            'record_id'  => $accountId,
            'note'       => 'Adjusted points: ' . $points . ' | Reason: ' . $reason,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->db->transComplete();
        return $this->db->transStatus();
    }
}
