<?php
/***************************************
** Title.........: HTML Mime Mail class
** Version.......: 1.31
** Author........: Richard Heyes <richard.heyes@heyes-computing.net>
** Filename......: html_mime_mail.class
** Last changed..: 31/08/2000
** Notes.........: Based upon mime_mail.class
**                 by Tobias Ratschiller <tobias@dnet.it>
**                 and Sascha Schumann <sascha@schumann.cx>.
**                 See http://www.heyes-computing.net/scripts/
**                 for full tar/zip if you haven't got one.
***************************************/

class html_mime_mail{

        var $mime;
        var $html;
        var $body;
        var $do_html;
        var $multipart;
        var $html_text;
        var $html_images = array();
        var $headers     = array();
        var $parts       = array();

/***************************************
** Constructor function. Sets the headers
** if supplied.
***************************************/
        function html_mime_mail($headers = ''){
                if($headers == '') return TRUE;
                if(is_string($headers)) $headers = explode("\r\n", trim($headers));
                for($i=0; $i<count($headers); $i++){
                        if(is_array($headers[$i])) for($j=0; $j<count($headers[$i]); $j++) if($headers[$i][$j] != '') $this->headers[] = $headers[$i][$j];
                        if($headers[$i] != '') $this->headers[] = $headers[$i];
                }
        }

/***************************************
** Accessor function to set the body text.
** Body text is used if it's not an html
** mail being sent.
***************************************/
        function set_body($text = ''){
                if(is_string($text)){
                        $this->body = $text;
                        return TRUE;
                }
                return FALSE;
        }

/***************************************
** Accessor function to return the mime
** class variable. Purely for debug, and
** to fall inline with OOP "rulez".
***************************************/
        function get_mime(){
                if(!isset($this->mime)) $this->mime = '';
                return $this->mime;
        }

/***************************************
** Function to set a header. Shouldn't
** really be necessary as you could use
** the constructor and send functions,
** it's here nonetheless. Takes any number
** of arguments, which can be either
** strings or arrays full of strings.
** This function is php4 only and will
** return false otherwise. Will return
** true upon finishing.
***************************************/
        function add_header(){
                if((int)phpversion() < 4) return FALSE;
                $args = func_get_args();
                for($i=0; $i<count($args); $i++){
                        if(is_array($args[$i])) for($j=0; $j<count($args[$i]); $j++) if($args[$i][$j] != '') $this->headers[] = $args[$i][$j];
                        if($args[$i] != '') $this->headers[] = $args[$i];
                }
                return TRUE;
        }

/***************************************
** Adds a html part to the mail.
** Also replaces image names with
** content-id's.
***************************************/
        function add_html($html, $text){
                $this->do_html   = 1;
                $this->html      = $html;
                $this->html_text = $text;
                if(is_array($this->html_images) AND count($this->html_images) > 0){
                        for($i=0; $i<count($this->html_images); $i++) $this->html = ereg_replace($this->html_images[$i]['name'], 'cid:'.$this->html_images[$i]['cid'], $this->html);
                }
        }

/***************************************
** Builds html part of email.
***************************************/
        function build_html($orig_boundary){
                $sec_boundary = '=_'.md5(uniqid(time()));
                $thr_boundary = '=_'.md5(uniqid(time()));

                if(count($this->html_images) == 0){
                        $this->multipart.= '--'.$orig_boundary."\r\n";
                        $this->multipart.= 'Content-Type: multipart/alternative;'.chr(13).chr(10).chr(9).'boundary="'.$sec_boundary."\"\r\n\r\n\r\n";

                        $this->multipart.= '--'.$sec_boundary."\r\n";
                        $this->multipart.= 'Content-Type: text/plain'."\r\n";
                        $this->multipart.= 'Content-Transfer-Encoding: base64'."\r\n\r\n";
                        $this->multipart.= chunk_split(base64_encode($this->html_text))."\r\n\r\n";

                        $this->multipart.= '--'.$sec_boundary."\r\n";
                        $this->multipart.= 'Content-Type: text/html'."\r\n";
                        $this->multipart.= 'Content-Transfer-Encoding: base64'."\r\n\r\n";
                        $this->multipart.= chunk_split(base64_encode($this->html))."\r\n\r\n";
                        $this->multipart.= '--'.$sec_boundary."--\r\n\r\n";
                }else{
                        $this->multipart.= '--'.$orig_boundary."\r\n";
                        $this->multipart.= 'Content-Type: multipart/related;'.chr(13).chr(10).chr(9).'boundary="'.$sec_boundary."\"\r\n\r\n\r\n";

                        $this->multipart.= '--'.$sec_boundary."\r\n";
                        $this->multipart.= 'Content-Type: multipart/alternative;'.chr(13).chr(10).chr(9).'boundary="'.$thr_boundary."\"\r\n\r\n\r\n";

                        $this->multipart.= '--'.$thr_boundary."\r\n";
                        $this->multipart.= 'Content-Type: text/plain'."\r\n";
                        $this->multipart.= 'Content-Transfer-Encoding: base64'."\r\n\r\n";
                        $this->multipart.= chunk_split(base64_encode($this->html_text))."\r\n\r\n";

                        $this->multipart.= '--'.$thr_boundary."\r\n";
                        $this->multipart.= 'Content-Type: text/html'."\r\n";
                        $this->multipart.= 'Content-Transfer-Encoding: base64'."\r\n\r\n";
                        $this->multipart.= chunk_split(base64_encode($this->html))."\r\n\r\n";
                        $this->multipart.= '--'.$thr_boundary."--\r\n\r\n";

                        for($i=0; $i<count($this->html_images); $i++){
                                $this->multipart.= '--'.$sec_boundary."\r\n";
                                $this->build_html_image($i);
                        }

                        $this->multipart.= "--".$sec_boundary."--\r\n\r\n";
                }
        }
/***************************************
** Adds an image to the list of embedded
** images.
***************************************/
        function add_html_image($file, $name = '', $c_type='application/octet-stream'){
                $this->html_images[] = array( 'body'   => $file,
                                              'name'   => $name,
                                              'c_type' => $c_type,
                                              'cid'    => md5(uniqid(time())) );
        }


/***************************************
** Adds a file to the list of attachments.
***************************************/
        function add_attachment($file, $name = '', $c_type='application/octet-stream'){
                $this->parts[] = array( 'body'   => $file,
                                        'name'   => $name,
                                        'c_type' => $c_type );
        }

/***************************************
** Builds an embedded image part of an
** html mail.
***************************************/
        function build_html_image($i){
                $this->multipart.= 'Content-Type: '.$this->html_images[$i]['c_type'];

                if($this->html_images[$i]['name'] != '') $this->multipart .= '; name="'.$this->html_images[$i]['name']."\"\r\n";
                else $this->multipart .= "\r\n";

                $this->multipart.= 'Content-Transfer-Encoding: base64'."\r\n";
                $this->multipart.= 'Content-ID: <'.$this->html_images[$i]['cid'].">\r\n\r\n";
                $this->multipart.= chunk_split(base64_encode($this->html_images[$i]['body']))."\r\n";
        }

/***************************************
** Builds a single part of a multipart
** message.
***************************************/
        function build_part($i){
                $message_part = '';
                $message_part.= 'Content-Type: '.$this->parts[$i]['c_type'];
                if($this->parts[$i]['name'] != '')
                        $message_part .= '; name="'.$this->parts[$i]['name']."\"\r\n";
                else
                        $message_part .= "\r\n";

                // Determine content encoding.
                if($this->parts[$i]['c_type'] == 'text/plain'){
                        $message_part.= 'Content-Transfer-Encoding: base64'."\r\n\r\n";
                        $message_part.= chunk_split(base64_encode($this->parts[$i]['body']))."\r\n";
                }elseif($this->parts[$i]['c_type'] == 'message/rfc822'){
                        $message_part.= 'Content-Transfer-Encoding: 7bit'."\r\n\r\n";
                        $message_part.= $this->parts[$i]['body']."\r\n";
                }else{
                        $message_part.= 'Content-Transfer-Encoding: base64'."\r\n";
                        $message_part.= 'Content-Disposition: attachment; filename="'.$this->parts[$i]['name']."\"\r\n\r\n";
                        $message_part.= chunk_split(base64_encode($this->parts[$i]['body']))."\r\n";
                }

                return $message_part;
        }

/***************************************
** Builds the multipart message from the
** list ($this->_parts).
***************************************/
        function build_message(){
                $boundary = '=_'.md5(uniqid(time()));

                $this->headers[] = 'MIME-Version: 1.0';
                $this->headers[] = 'Content-Type: multipart/mixed;'.chr(13).chr(10).chr(9).'boundary="'.$boundary.'"';
                $this->multipart = "This is a MIME encoded message.\r\n\r\n";

                if(isset($this->do_html) AND $this->do_html == 1) $this->build_html($boundary);
                if(isset($this->body) AND $this->body != '') $this->parts[] = array('body' => $this->body, 'name' => '', 'c_type' => 'text/plain');

                for($i=(count($this->parts)-1); $i>=0; $i--){
                        $this->multipart.= '--'.$boundary."\r\n".$this->build_part($i);
                }

                $this->mime = $this->multipart."--".$boundary."--\r\n";
        }

/***************************************
** Sends the mail.
***************************************/
        function send($to_name, $to_addr, $from_name, $from_addr, $subject = '', $headers = ''){

                if($to_name != '') $to = '"'.$to_name.'" <'.$to_addr.'>';
                else $to = $to_addr;

                if($from_name != '') $from = '"'.$from_name.'" <'.$from_addr.'>';
                else $from = $from_addr;

                if(is_string($headers)) $headers = explode("\r\n", trim($headers));
                for($i=0; $i<count($headers); $i++){
                        if(is_array($headers[$i])) for($j=0; $j<count($headers[$i]); $j++) if($headers[$i][$j] != '') $xtra_headers[] = $headers[$i][$j];
                        if($headers[$i] != '') $xtra_headers[] = $headers[$i];
                }
                if(!isset($xtra_headers)) $xtra_headers = array();

                // mail($to, $subject, $this->mime, 'From: '.$from."\r\n".implode("\r\n", $this->headers)."\r\n".implode("\r\n", $xtra_headers));
                
                ## Testing the basics
                /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
                $tMsg = "<b>Email Settings:</b><br>\n";
                $tMsg .= "To: [".$to."]<br>\n";
                $tMsg .= "Subject: [".$subject."]<br>\n";
                
                $tMsg .= "<u>Message</u>:<br>\n";
                $tMsg .= "<p style=\"border: 1px solid #d70000;\">".$this->mime."</p><br><br>\n";
                
                $tMsg .= "<u>Headers</u>:<br>\n";
                $tMsg .= "<p style=\"border: 1px solid #0066FF;\">From: ".$from."\r\n".implode("\r\n", $this->headers)."\r\n".implode("\r\n", $xtra_headers)."</p>;
                
                mail($to, $subject, $tMsg);
                //mail("mike.morrison@soholaunch.com", "Hard-Coded", "This is my hard coded message that\n really\n should\n work");
                /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
                
        }

/***************************************
** Use this method to deliver using direct
** smtp connection. Relies upon Manuel Lemos'
** smtp mail delivery class available at:
** http://phpclasses.upperdesign.com
**
** void smtp_send( string *Name* of smtp object,
**                 string From address,
**                 array  To addresses,
**                 string Subject,
**                 array  Extra headers)
***************************************/
        function smtp_send($smtp_obj, $from_addr, $to_addr, $subject, $xtra_headers = ''){
                global $$smtp_obj;
                $smtp_obj = $$smtp_obj;

                $headers   = $this->headers;
                $headers[] = 'From: '.$from_addr;
                $headers[] = 'Subject: '.$subject;
                if(is_array($xtra_headers)) for(reset($xtra_headers); list(,$header) = each($xtra_headers); ) $headers[] = $header;

                // The following: sendmessage(string from address, array to addresses, array headers, string body)
                $smtp_obj->sendmessage($from_addr, $to_addr, $headers, $this->mime);
        }

/***************************************
** Use this method to return the email
** in message/rfc822 format. Useful for
** adding an email to another email as
** an attachment. There's a commented
** out example in example.php.
**
** string get_rfc822(string To name,
**                   string To email,
**                   string From name,
**                   string From email,
**                   [string Subject,
**                    string Extra headers])
***************************************/
        function get_rfc822($to_name, $to_addr, $from_name, $from_addr, $subject = '', $headers = ''){

                // Make up the date header as according to RFC822
                $date = 'Date: '.date('D, d M y H:i:s');

                if($to_name != '') $to = 'To: "'.$to_name.'" <'.$to_addr.'>';
                else $to = $to_addr;

                if($from_name != '') $from = 'From: "'.$from_name.'" <'.$from_addr.'>';
                else $from = $from_addr;

                if(is_string($subject)) $subject = 'Subject: '.$subject;

                if(is_string($headers)) $headers = explode("\r\n", trim($headers));
                for($i=0; $i<count($headers); $i++){
                        if(is_array($headers[$i])) for($j=0; $j<count($headers[$i]); $j++) if($headers[$i][$j] != '') $xtra_headers[] = $headers[$i][$j];
                        if($headers[$i] != '') $xtra_headers[] = $headers[$i];
                }
                if(!isset($xtra_headers)) $xtra_headers = array();

                return $date."\r\n".$from."\r\n".$to."\r\n".$subject."\r\n".implode("\r\n", $this->headers)."\r\n".implode("\r\n", $xtra_headers)."\r\n\r\n".$this->mime;
        }


} // End of class.
?>
