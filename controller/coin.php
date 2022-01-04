<?php

include "../../config/config.php";


class coins extends Connection
{
    public static function current_ico()
    {
        $sql = "SELECT * FROM coin WHERE statuz =1 LIMIT 1";
        $query = self::$connect->query($sql);
        if ($query) {
            while ($rows = $query->fetch_object()) {
                return self::Response(200, 'success', '', $rows);
            }
        } else {
            return self::Response(501, 'failed', 'internal error' . self::$connect->error, '');
        }
    }

    public static function create_ico($name, $qty, $price, $bnb, $btc, $eth, $ends_on, $purchased)
    {
        $coin_id = uniqid("whalesInu");
        $sql = "INSERT INTO  coin (coin_id,coin_name,qty,price,price_bnb,price_btc,price_eth,ends_on,purchased)
         VALUES (?,?,?,?,?,?,?,?,?)";
        $query = self::$connect->prepare($sql);
        $query->bind_param("ssisssssi", $coin_id, $name, $qty, $price, $bnb, $btc, $eth, $ends_on, $purchased);
        $query->execute();
        if ($query->affected_rows > 0) {
            return self::Response(200, "success", $name . " created successfully", "");
        } else {
            return self::Response(500, "failed", "internal server error =>" . $query->error, "");
        }
    }
    public static function mark_active($coinid)
    {
        $sql = "SELECT * FROM coin";
        $query = self::$connect->query($sql);
        if ($query) {
            while ($row = $query->fetch_object()) {
                if ((string)$row->coin_id === (string)$coinid) {
                    $sql2 = "UPDATE coin SET statuz = 1 WHERE id=$row->id";
                    $q = self::$connect->query($sql2);
                } else {
                    $sql2 = "UPDATE coin SET statuz = 0 WHERE id=$row->id";
                    $q = self::$connect->query($sql2);
                }
            }
            return self::Response(200, "success",  " Loaded successfully", "");
        } else {
            return self::Response(500, "failed", "internal server error =>" . self::$connect->error, "");
        }
    }

    public static function delete_ico($coinid)
    {
        $sql = "DELETE FROM coin WHERE coin_id='$coinid'";
        $query = self::$connect->query($sql);
        if ($query) {
            return self::Response(200, "success", $coinid . " deleted successfully", "");
        } else {
            return self::Response(500, "failed", "internal server error =>" . $query->error, "");
        }
    }

    public static function get_all_ico()
    {
        $data = [];
        $sql = "SELECT * FROM coin";
        $query = self::$connect->query($sql);
        if ($query) {
            while ($row = $query->fetch_object()) {
                $data[$row->id] = $row;
            }
            return self::Response(200, "success",  " Loaded successfully", $data);
        } else {
            return self::Response(500, "failed", "internal server error =>" . self::$connect->error, "");
        }
    }
}