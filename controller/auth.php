<?php

include "../../config/config.php";

class Auth extends Connection
{
    public static  function Login($email, $password)
    {
        $email = self::filter($email);
        $password = self::filter($password);
        $sql = "SELECT * FROM users WHERE email = ? ";
        $query = self::$connect->prepare($sql);
        $query->bind_param('s', $email);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                //if(password_verify($password,$row->pass)){
                if ($password === $row->pass) {
                    if((bool) $row->isverfied){
                    $hash = uniqid() . $row->userid;
                    $_SESSION["userid"] = $row->userid;
                    $data["user"] = [
                        'userid' => $row->userid,
                        'isadmin' => (bool)$row->isadmin,
                        'fullname' => $row->fullname,
                        'email' => $row->email
                    ];
                    $data['hash'] = $hash;
                    return self::Response(200, 'success', 'login successful', $data);
                }else{
                    return self::Response(403, 'failed', 'please verify your email and try again', '');                
                }
                } else {
                    return self::Response(403, 'failed', 'incorrect password. check the email or password', '');
                }
            }
        } else {
            return self::Response(404, 'failed', 'no user found. check the email', '');
        }
    }

    public  static function userdetails($userid)
    {

        $sql = "SELECT * FROM users WHERE userid = ? ";
        $query = self::$connect->prepare($sql);
        $query->bind_param('s', $userid);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_object()) {

                $data = [
                    'userid' => $row->userid,
                    'fullname' => $row->fullname,
                    'email' => $row->email,
                    'act_bal' => $row->act_bal,
                    'wallet' => $row->wallet,
                    'createdAt' => $row->createdAt
                ];
                return self::Response(200, 'success', '', $data);
            }
        } else {
            return self::Response(404, 'failed', 'no user found', '');
        }
    }


    public static function updatesettings($userid, $username, $email)
    {
        $sql = "UPDATE users SET fullname=?, email = ? WHERE userid =?";
        $query = self::$connect->prepare($sql);
        $query->bind_param('sss', $username, $email, $userid);
        $query->execute();
        if ($query->affected_rows > 0) {
            return self::Response(200, 'success', 'details updated successfully', '');
        } else {
            return self::Response(500, 'failed', "unable to update details" . self::$connect->eror, '');
        }
    }

    public static function Changepassword($userid, $oldpassword, $newpassword, $email)
    {

        $sql1 = "SELECT pass FROM users WHERE userid='$userid'";
        $qu = self::$connect->query($sql1);
        if ($qu) {
            $row = $qu->fetch_object();
            if ($row->pass === $oldpassword) {
                $sql = "UPDATE users SET pass=? WHERE userid =?";
                $query = self::$connect->prepare($sql);
                $query->bind_param('ss', $newpassword, $userid);
                $query->execute();
                if ($query->affected_rows > 0) {
                    return self::Response(200, 'success', 'details updated successfully', '');
                    $body = '
                        <div style="background:#e0e8f3;padding-top:20px;padding-left:4px;padding-right:4px">
                              <div style="background:#fafafa;padding:3px>
                               <h4>hello there</h4>
                              </div>
                        </div>
                    ';
                    self::sendmail('shipliveinc@gmail.com', "", "change to whalesinu password", $body);
                } else {
                    return self::Response(500, 'failed', "unable to update details" . self::$connect->eror, '');
                }
            } else {
                return self::Response(401, 'failed', "old password doesnt match", '');
            }
        } else {
            return self::Response(404, 'failed', "User Not Found", '');
        }
    }

    static function addWallet($userid, $wallet)
    {
        $sql = "UPDATE users SET wallet=? WHERE userid =?";
        $query = self::$connect->prepare($sql);
        $query->bind_param('ss', $wallet, $userid);
        $query->execute();
        if ($query->affected_rows > 0) {
            return self::Response(200, 'success', 'wallet updated successfully', '');
        } else {
            return self::Response(500, 'failed', "unable to update details" . self::$connect->eror, '');
        }
    }

}