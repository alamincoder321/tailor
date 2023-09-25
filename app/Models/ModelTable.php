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

    public static function tailorDue($id = null)
    {
        $clauses = "";
        if ($id != null) {
            $clauses .= "WHERE t.id = $id";
        }
        return DB::select("SELECT
                        t.name,
                        t.mobile,
                        t.address,
                        (SELECT IFNULL(SUM(clt.tailor_total), 0)
                            FROM order_items AS clt
                            LEFT JOIN orders cl ON cl.id = clt.order_id
                            WHERE cl.deleted_at IS NULL AND clt.tailor_id = t.id) AS billAmount,
                            (SELECT IFNULL(SUM(clt.paid), 0)
                            FROM order_items AS clt
                            LEFT JOIN orders cl ON cl.id = clt.order_id
                            WHERE cl.deleted_at IS NULL AND clt.tailor_id = t.id) AS paidAmount,
                        (SELECT IFNULL(SUM(tp.amount), 0)
                            FROM tailor_payments AS tp
                            WHERE tp.deleted_at IS NULL AND tp.tailor_id = t.id) AS tailorPaymentAmount,
                        (SELECT (billAmount) - (paidAmount+tailorPaymentAmount)) AS due
                    FROM tailors AS t $clauses");
    }
}
