<?php

namespace ZK;

if (!class_exists(__NAMESPACE__.'\\Encoding')) {
    final class Encoding
    {
        public static function toUtf8Safe($s)
        {
            if ($s === null) return '';
            if (is_array($s)) $s = reset($s);
            $s = (string)$s;
            if ($s === '') return '';
            if (mb_check_encoding($s, 'UTF-8')) return $s;

            foreach (['Windows-1252','ISO-8859-1','GBK','BIG5','SJIS'] as $enc) {
                $t = @mb_convert_encoding($s, 'UTF-8', $enc);
                if ($t !== false && mb_check_encoding($t, 'UTF-8')) return $t;
            }
            $t = @iconv('UTF-8', 'UTF-8//IGNORE', $s);
            return $t !== false ? $t : '';
        }
    }
}

if (!function_exists(__NAMESPACE__.'\\to_utf8_safe')) {
    function to_utf8_safe($s) { return Encoding::toUtf8Safe($s); }
}

use ZKLib;

class User
{
    /**
     * @param ZKLib $self
     * @param int $uid Unique ID (max 65535)
     * @param int|string $userid (max length = 9, only numbers - depends device setting)
     * @param string $name (max length = 24)
     * @param int|string $password (max length = 8, only numbers - depends device setting)
     * @param int $role Default Util::LEVEL_USER
     * @param int $cardno Default 0 (max length = 10, only numbers)
     * @return bool|mixed
     */
    public function set(ZKLib $self, $uid, $userid, $name, $password, $role = Util::LEVEL_USER, $cardno = 0)
    {
        $self->_section = __METHOD__;

        if (
            (int)$uid === 0 ||
            (int)$uid > Util::USHRT_MAX ||
            strlen($userid) > 9 ||
            strlen($name) > 24 ||
            strlen($password) > 8 ||
            strlen($cardno) > 10
        ) {
            return false;
        }

        $command = Util::CMD_SET_USER;
        $byte1 = chr((int)($uid % 256));
        $byte2 = chr((int)($uid >> 8));
        $cardno = hex2bin(Util::reverseHex(dechex($cardno)));

        $command_string = implode('', [
            $byte1,
            $byte2,
            chr($role),
            str_pad($password, 8, chr(0)),
            str_pad($name, 24, chr(0)),
            str_pad($cardno, 4, chr(0)),
            str_pad(chr(1), 9, chr(0)),
            str_pad($userid, 9, chr(0)),
            str_repeat(chr(0), 15)
        ]);

        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKLib $self
     * @return array [userid, name, cardno, uid, role, password]
     */
    public function get(ZKLib $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_USER_TEMP_RRQ;
        $command_string = chr(Util::FCT_USER);

        $session = $self->_command($command, $command_string, Util::COMMAND_TYPE_DATA);
        if ($session === false) {
            return [];
        }

        $userData = Util::recData($self);

        $users = [];
        if (!empty($userData)) {
            $userData = substr($userData, 11);

            while (strlen($userData) > 72) {
                $u = unpack('H144', substr($userData, 0, 72));

                $u1 = hexdec(substr($u[1], 2, 2));
                $u2 = hexdec(substr($u[1], 4, 2));
                $uid = $u1 + ($u2 * 256);
                $cardno = hexdec(substr($u[1], 78, 2) . substr($u[1], 76, 2) . substr($u[1], 74, 2) . substr($u[1], 72, 2)) . ' ';
                $role = hexdec(substr($u[1], 6, 2)) . ' ';
                $password = hex2bin(substr($u[1], 8, 16)) . ' ';
                $name = hex2bin(substr($u[1], 24, 74)) . ' ';
                $userid = hex2bin(substr($u[1], 98, 72)) . ' ';

                //Clean up some messy characters from the user name
                $password = explode(chr(0), $password, 2);
                $password = $password[0];
                $userid = explode(chr(0), $userid, 2);
                $userid = $userid[0];
                $name = explode(chr(0), $name, 3);
                $name = to_utf8_safe($name[0]);
                    
                $first = function($v) {
                    if (is_array($v)) $v = reset($v);
                    $s = (string)($v ?? '');
                    if ($s !== '') $s = explode("\0", $s, 2)[0]; // buang NUL terminator
                    return $s;
                };

                $userid = $first($userid);
                $name   = \ZK\Encoding::toUtf8Safe($first($name));
                $cardno = $first($cardno);

                if ($cardno !== '') {
                    $cardno = preg_replace('/\D+/', '', $cardno);
                    $cardno = $cardno === '' ? '' : str_pad($cardno, 11, '0', STR_PAD_LEFT);
                }

                if ($name === '') {
                    $name = $userid;
                }

                $users[$userid] = [
                    'userid' => $userid,
                    'name' => $name,
                    'cardno' => $cardno,
                    'uid' => $uid,
                    'role' => intval($role),
                    'password' => $password
                ];

                $userData = substr($userData, 72);
            }
        }

        return $users;
    }

    /**
     * @param ZKLib $self
     * @return bool|mixed
     */
    public function clear(ZKLib $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_CLEAR_DATA;
        $command_string = '';

        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKLib $self
     * @return bool|mixed
     */
    public function clearAdmin(ZKLib $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_CLEAR_ADMIN;
        $command_string = '';

        return $self->_command($command, $command_string);
    }

    /**
     * @param ZKLib $self
     * @param integer $uid
     * @return bool|mixed
     */
    public function remove(ZKLib $self, $uid)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DELETE_USER;
        $byte1 = chr((int)($uid % 256));
        $byte2 = chr((int)($uid >> 8));
        $command_string = ($byte1 . $byte2);

        return $self->_command($command, $command_string);
    }
}