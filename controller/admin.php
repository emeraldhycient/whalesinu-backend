<?php

include_once('../../config/config.php');

class admin extends Connection
{

    public static function users()
    {
        $data = [];
        $sql = "SELECT * FROM users";
        $query = self::$connect->prepare($sql);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $data[$row->id] = $row;
            }
            return self::Response(200, "success", "users found", $data);
        } else {
            return self::Response(404, "failed", "no user found", '');
        }
    }

    public static function Deposits()
    {
        $data = [];
        $sql = "SELECT * FROM depositrequest ORDER BY id DESC";
        $query = self::$connect->prepare($sql);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $data[$row->id] = $row;
            }
            return self::Response(200, "success", "deposits fouund", $data);
        } else {
            return self::Response(404, "failed", "no deposit found", '');
        }
    }

    public static function processdeposit($tx_rf, $userid, $amount, $coinid)
    {
        $status = 1;
        $userid = self::filter($userid);
        $sql = "UPDATE depositrequest SET statuz = ? WHERE tx_ref = ?";
        $query = self::$connect->prepare($sql);
        $query->bind_param('is', $status, $tx_rf);
        $query->execute();
        if ($query->affected_rows > 0) {
            $sql2 = "UPDATE users SET  act_bal=act_bal + $amount  WHERE userid ='$userid'";
            $query2 = self::$connect->query($sql2);
            if ($query2) {
                self::updatePurchased($amount, $coinid);
                self::depositapprovalnotifier($userid,$tx_rf,$amount,'approved');
                return self::Response(200, 'success', 'contribution request processed ', '');
            } else {
                return self::Response(500, "failed", "unable to process contribution request because this user doesnt seem to exist", self::$connect->error);
            }
        } else {
            return self::Response(500, "failed", "unable to process contribution request", "");
        }
    }

    public static function updatePurchased($amount, $coinid)
    {
        $sql2 = "UPDATE coin SET  purchased=purchased + $amount  WHERE coin_id ='$coinid'";
        $query2 = self::$connect->query($sql2);
        if ($query2) {
            return self::Response(200, 'success', 'contribution request processed ', '');
        } else {
            return self::Response(500, "failed", "unable to process contribution request because this user doesnt seem to exist", self::$connect->error);
        }
    }


    public static function paymentMethods()
    {
        $data = [];
        $sql = "SELECT * FROM paymentmethod";
        $query = self::$connect->prepare($sql);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $data = $row;
            }
            return self::Response(200, "success", "paymentmethods found", $data);
        } else {
            return self::Response(404, "failed", "no paymentmethods found", '');
        }
    }

    public static function updatepaymentmethods($id, $bitcoin, $ethereum, $bnb,$usdt, $instruction)
    {
        $bitcoin = self::filter($bitcoin);
        $ethereum = self::filter($ethereum);
        $litecoin = self::filter($bnb);
        $paypal = self::filter($instruction);
        $sql = "UPDATE paymentmethod SET bitcoin = ? , ethereum = ? ,bnb = ?,usdt=?, instructions = ? WHERE id =?";
        $query = self::$connect->prepare($sql);
        $query->bind_param("sssssi", $bitcoin, $ethereum, $litecoin, $usdt,$paypal, $id);
        $query->execute();
        if ($query->affected_rows > 0) {
            return self::Response(200, "success", "payment methods updated", '');
        } else {
            return self::Response(500, "failed", "unable to  update payment methods ", '');
        }
    }


    public static function totalDeposit()
    {
        $sql = "SELECT SUM(amount) as amount FROM  depositrequest";
        $query = self::$connect->query($sql);
        $data = $query->fetch_array();
        if ($data) {
            return self::Response(200, "success", "woolah", $data);
        } else {
            return self::Response(404, "failed", "nothing found", $data);
        }
    }

    public static function totalusers()
    {
        $sql = "SELECT COUNT(*) as users FROM  users";
        $query = self::$connect->query($sql);
        $data = $query->fetch_array();
        if ($data) {
            return self::Response(200, "success", "woolah", $data);
        } else {
            return self::Response(404, "failed", "nothing found", $data);
        }
    }

    public static function deleteuser($userid)
    {
        $sql = " DELETE FROM users  WHERE userid = ?";
        $query = self::$connect->prepare($sql);
        $query->bind_param("s", $userid);
        $query->execute();
        if ($query->affected_rows > 0) {
            return self::Response(200, "success", "user deleted successfully", '');
        } else {
            return self::Response(500, "failed", "unable to delete user" . $query->error, '');
        }
    }

    public  static function userdetails($hash, $userid)
    {

        $sql = "SELECT * FROM users WHERE userid = ? ";
        $query = self::$connect->prepare($sql);
        $query->bind_param('s', $userid);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_object()) {

                $data = $row;
                return self::Response(200, 'success', '', $data);
            }
        } else {
            return self::Response(404, 'failed', 'no user found', '');
        }
    }

    public static function depositapprovalnotifier($userid,$tx_rf,$amount,$status){
        $sql ="SELECT * FROM users WHERE userid=?";
        $query=self::$connect->prepare($sql);
        $query->bind_param('s',$userid);
        $query->execute();
        $result =$query->get_result();
        if($result->num_rows > 0){
            while($row = $result->fetch_object()){
                $body = `
                <p>your transaction "$tx_rf"  of "$amount" has been "$status" </p>
                `;
                self::sendmail($row->email,$row->fullname,"whalesinu token $status",$body);
            }
        }
    }
}