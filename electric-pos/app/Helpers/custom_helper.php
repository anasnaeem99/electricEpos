<?php

use Config\Database;
use CodeIgniter\Config\Services;
use App\Views\phptextClass;

function pre($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

/**
 * This function used to get the CI instance
 */
if(!function_exists('get_instance'))
{
    function get_instance()
    {
        $CI = &get_instance();
    }
}

/**
 * This function used to generate the hashed password
 * @param {string} $plainPassword : This is plain text password
 */
if(!function_exists('getHashedPassword'))
{
    function getHashedPassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }
}

/**
 * This function used to generate the hashed password
 * @param {string} $plainPassword : This is plain text password
 * @param {string} $hashedPassword : This is hashed password
 */
if(!function_exists('verifyHashedPassword'))
{
    function verifyHashedPassword($plainPassword, $hashedPassword)
    {
        return password_verify($plainPassword, $hashedPassword) ? true : false;
    }
}

if (!function_exists('setProtocol')) {
    function setProtocol()
    {
        // Load email configuration directly from the config file
        $emailConfig = [
            'protocol' => 'smtp',
            'SMTPHost' => 'smtp.gmail.com',
            'SMTPPort' => 587,
            'SMTPUser' => 'anasnaeem37@gmail.com',
            'SMTPPass' => 'vecs crkj mwqk ermo',
            'SMTPAuth' => true,
            'SMTPCrypto' => 'tls',  // Set as 'none' if you don't use encryption
            'charset' => 'UTF-8',
            'mailType' => 'html',
            'newline' => "\r\n",
        ];

        // Initialize the email service with the configuration
        $email = Services::email(); // CodeIgniter 4's way to access the email service
        $email->initialize($emailConfig); // Initialize with the provided config

        return $email; // Return the email service object
    }
}


if (!function_exists('mailSend')) {
    function mailSend($detail)
    {
        // Initialize the email service
        $email = setProtocol(); // Get the email service instance from the updated setProtocol

        // Prepare the email data
        $data['data'] = $detail;
        $email->setFrom('support@rbi.pqmplustools.com', $detail['from']);
        $email->setTo($detail['email']);
        $email->setSubject($detail['subject']);
        
        // Set the email message using a view
        $email->setMessage(view('email/mailTemplate', $data)); // Load the email content from the view

        // Attach a file if provided
        if (isset($detail['attach'])) {
            $email->attach($detail['attach']);
        }

        // Send the email
        if ($email->send()) {
            return true;  // Email sent successfully
        } else {
            return false;  // Email failed to send
        }
    }
}


if (!function_exists('setFlashData')) {
    function setFlashData($status, $flashMsg)
    {
        $session = \Config\Services::session(); // Load the session service in CI4
        $session->setFlashdata($status, $flashMsg); // Set flash data
    }
}

// This function used to get the database data...
if(!function_exists('myGet'))
{
    function myGet($table, $selectQuery, $whereClause, $orderByColumn, $order)
    {
        // Get the database connection
        $db = \Config\Database::connect();
        
        // Build the query
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select($selectQuery, false) // Use false to prevent escaping
                ->where('BaseTbl.isDeleted', 0);

        if ($whereClause) {
            $builder->where($whereClause);
        }

        $builder->orderBy($orderByColumn, $order);
        $query = $builder->get();

        return $query->getResult();
    }
}

   // Record Save Operation
    function recordAjaxSave($data, $table)
    {   
        // Get the database connection
        $db = \Config\Database::connect();

        // Insert data into the main table
        $builder = $db->table($table);
        $builder->insert($data);
        $insertId = $db->insertID();

        // Log the query in the audit log
        $theQuery = $db->getLastQuery();

        return $insertId;
    }

    // Record Update Operation
    function recordAjaxUpdate($idColumn, $id, $data, $table)
    {   
        // Get the database connection
        $db = \Config\Database::connect();
        // Use the table method of CodeIgniter 4 query builder
        $builder = $db->table($table);

        // Perform the update operation
        $builder->where($idColumn, $id);
        $builder->update($data);

        // Get the number of affected rows (updated rows)
        $update_id = $db->affectedRows();

        // Get the last executed query for auditing purposes
        $theQuery = $db->getLastQuery();

        return $update_id;
    }


