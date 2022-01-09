<?php

include "../../config/config.php";

class users extends Connection
{

    public static function createaccount($fullname, $password, $email,  $isadmin)
    {
        $userid = uniqid();
        $fullname = self::filter($fullname);
        $password = self::filter($password);
        //$hashpassword = password_hash($password,PASSWORD_BCRYPT);
        $email = self::filter($email);

        $sql = "INSERT INTO users (userid,fullname,pass,email,isAdmin) VALUES 
        (?,?,?,?,?)";
        $query = self::$connect->prepare($sql);
        $query->bind_param("ssssi", $userid, $fullname, $password, $email, $isadmin);
        $query->execute();
        if ($query->affected_rows > 0) {
            self::sendToken(self::generateToken(),$email,$fullname);
            return self::Response(200, 'success', "signup successfully check email to verify account", '');
        } else {
            return self::Response(200, 'failed', "unable to signup user" . $query->error, '');
        }
    }

    public static function updateuser($userid, $fullname, $password, $email, $accountbalance, $wallet, $isadmin)
    {
        //$hashpassword = password_hash($password,PASSWORD_BCRYPT);
        $sql = "UPDATE users SET fullname =' $fullname',pass ='$password',email='$email',act_bal=$accountbalance,wallet='$wallet',isadmin=$isadmin WHERE userid = '$userid'";
        $query = self::$connect->query($sql);
        if ($query) {
            return self::Response(200, 'success', "user update  successfully", '');
        } else {
            return self::Response(500, 'failed', "unable to update user" . self::$connect->error, '');
        }
    }


    public static function deleteRequest($tx_ref)
    {
        $sql = "DELETE  FROM depositrequest WHERE tx_ref='$tx_ref'";
        $query = self::$connect->query($sql);
        if ($query) {
            return self::Response(200, 'success', "trans deleted successfully", '');
        } else {
            return self::Response(404, 'failed', "unable to delet this transaction", '');
        }
    }

    public static  function DepositRequest($userid, $coinid, $qty, $price, $currency, $wallet)
    {
        $tx_ref = "TNX" . rand(mt_rand(200, 1000), mt_rand(200, 1000));
        $sql = "INSERT INTO depositrequest (userid,coin_id,qty,price,currency,tx_ref,wallet) VALUES (?,?,?,?,?,?,?)";
        $query = self::$connect->prepare($sql);
        $query->bind_param("sssssss", $userid,  $coinid, $qty, $price, $currency, $tx_ref, $wallet);
        $query->execute();
        if ($query->affected_rows > 0) {
            return self::Response(200, "success", "Contribution  placed successfully", '');
        } else {
            return self::Response(500, "failed", "no unable to place deposit", '');
        }
    }


    public static function allTransactions($userid)
    {
        $data = [];
        $sql = "SELECT * FROM depositrequest  WHERE userid = ? ORDER BY id DESC";
        $query = self::$connect->prepare($sql);
        $query->bind_param('s', $userid);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $data[$row->id] = $row;
            }
            return self::Response(200, 'success', 'transactions found', $data);
        } else {
            return self::Response(404, 'failed', 'no transactions found', '');
        }
    }

    
    static function generateToken(){
         $token = uniqid('whal');
         if($token){
             return $token;
         }else{
             return false;
         }
    }

    static function verifyuser($token){
        
        $tk = "SELECT * FROM  tokens WHERE token = ?";
        $qk = self::$connect->prepare($tk);
        $qk->bind_param('s',$token);
        $qk->execute();
        $rk = $qk->get_result();
        if($rk->num_rows > 0){
            while($row = $rk->fetch_object()){
                $indicator = 1;
                $sql = "UPDATE users SET isverfied = ? WHERE email =?";
                $query= self::$connect->prepare($sql);
                $query->bind_param('is',$indicator,$row->email);
                $query->execute();
                if($query->affected_rows > 0){
                    return self::Response(200,"success","account verified successful","");
                }else{
                    return self::Response(500,"failed","internal server errors. pls contact admin",""); 
                }
            }
        }else{
            return self::Response(404,"failed","invalid token or token not found","");
        }

    }

    public static function sendToken ($token,$useremail,$username){
        $sql = "INSERT INTO  tokens (token,email) VALUES (?,?)";
        $query =self::$connect->prepare($sql);
        $query->bind_param('ss',$token,$useremail);
        $query->execute();
        if($query->affected_rows > 0){
            $body = '
            <p>please verify your whalesinu account</p>
            <a href="https://api.whalesinu.com/api/user/verifyuser.php?token='.$token.'">verify email</a>
            ';
    
            self::sendmail($useremail,$username,'verify email -whalesinu',$body);
            return true;
        }else{
            return false;
        }
     
    }
    static function verifycurrentuser(){
        $tk = "SELECT * FROM  users";
        $qk = self::$connect->prepare($tk);
        $qk->execute();
        $rk = $qk->get_result();
        if($rk->num_rows > 0){
            while($row = $rk->fetch_object()){
                $indicator = 1;
                $sql = "UPDATE users SET isverfied = ? WHERE email =?";
                $query= self::$connect->prepare($sql);
                $query->bind_param('is',$indicator,$row->email);
                $query->execute();
                if($query->affected_rows > 0){
                    echo "account verified successful => $row->email";
                }else{
                    echo "internal server errors. pls contact admin => $row->email";
                }
            }
        }else{
            echo "nothing";
        }
    }
}