<?php
namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'tbl_supplier';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'business_id', 'name', 'code', 'contact_person', 'phone', 'email',
        'ntn', 'payment_terms', 'opening_balance', 'is_active'
    ];

    public function getAllSuppliers($businessId)
    {
        return $this->db->table('tbl_supplier s')
            ->select('s.*, (s.opening_balance + COALESCE(SUM(sl.credit), 0) - COALESCE(SUM(sl.debit), 0)) as current_balance')
            ->join('tbl_supplier_ledger sl', 'sl.supplier_id = s.id', 'left')
            ->where('s.business_id', $businessId)
            ->where('s.is_active', 1)
            ->groupBy('s.id')
            ->orderBy('s.name', 'ASC')
            ->get()->getResult();
    }

    public function getSupplierById($id)
    {
        $supplier = $this->db->table('tbl_supplier')->where('id', $id)->get()->getRow();
        if ($supplier) {
            $ledgerSum = $this->db->table('tbl_supplier_ledger')
                ->select('COALESCE(SUM(debit), 0) as total_debit, COALESCE(SUM(credit), 0) as total_credit')
                ->where('supplier_id', $id)
                ->get()->getRow();

            $supplier->current_balance = $supplier->opening_balance + ($ledgerSum->total_credit - $ledgerSum->total_debit);
        }
        return $supplier;
    }

    public function getLedger($supplierId, $limit = 50)
    {
        return $this->db->table('tbl_supplier_ledger')
            ->where('supplier_id', $supplierId)
            ->orderBy('id', 'DESC')
            ->limit($limit)
            ->get()->getResult();
    }

    public function addLedgerEntry($supplierId, $refType, $refId, $debit, $credit, $note = '')
    {
        // Calculate new running balance
        $last = $this->db->table('tbl_supplier_ledger')
            ->where('supplier_id', $supplierId)
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()->getRow();

        $prevBal = $last ? $last->balance : ($this->getSupplierById($supplierId)->opening_balance ?? 0);
        $newBal = $prevBal + $credit - $debit;

        return $this->db->table('tbl_supplier_ledger')->insert([
            'supplier_id' => $supplierId,
            'ref_type'    => $refType,
            'ref_id'      => $refId,
            'debit'       => $debit,
            'credit'      => $credit,
            'balance'     => $newBal,
            'note'        => $note,
        ]);
    }

    public function recordPayment($data)
    {
        $paymentNo = 'PAY-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        $this->db->table('tbl_supplier_payment')->insert([
            'business_id'  => $data['business_id'],
            'supplier_id'  => $data['supplier_id'],
            'payment_no'   => $paymentNo,
            'payment_date' => $data['payment_date'] ?? date('Y-m-d'),
            'amount'       => $data['amount'],
            'method'       => $data['method'] ?? 'cash',
            'reference'    => $data['reference'] ?? null,
            'note'         => $data['note'] ?? null,
            'created_by'   => $data['created_by'],
        ]);
        $payId = $this->db->insertID();

        // Add ledger debit
        $this->addLedgerEntry($data['supplier_id'], 'payment', $payId, $data['amount'], 0, 'Payment ' . $paymentNo);

        return $payId;
    }
}
