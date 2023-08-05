<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModelTable extends Model
{
    use HasFactory;


    public static function customerDue($id = null)
    {
        $clauses = "";
        if ($id != null) {
            $clauses .= "WHERE c.id = $id";
        }
        return DB::select("SELECT
                        c.name,
                        c.customer_code,
                        c.phone,
                        c.address,
                        (SELECT IFNULL(SUM(om.total), 0)
                            FROM orders AS om
                            WHERE om.deleted_at IS NULL AND om.customer_id = c.id) AS billAmount,
                        (SELECT IFNULL(SUM(om.advance), 0)
                            FROM orders AS om
                            WHERE om.deleted_at IS NULL AND om.customer_id = c.id) AS paidAmount,
                        (SELECT IFNULL(SUM(cp.amount), 0)
                            FROM customer_payments AS cp
                            WHERE cp.deleted_at IS NULL AND cp.customer_id = c.id) AS customerPaymentAmount,
                        (SELECT (billAmount+c.previous_due) - (paidAmount+customerPaymentAmount)) AS due
                    FROM customers AS c $clauses");
    }
}
