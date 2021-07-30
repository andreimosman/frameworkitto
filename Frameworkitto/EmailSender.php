<?php
/**
 * Sends email using templates.
 * 
 * Requires PHPMailer
 * 
 * Before instantiate this class you should Call EmailSender::setConfig($config), 
 * preferably at /index.php as the example bellow:
 * 
 * 
 EmailSender::setConfig([
     "host" => "mail.mymailserver.com",
     "auth" => true,
     "username" => "me@mymailserver.com",
     "password" => "myverysecurepassword",
     "from_email" => "noreply@mymailserver.com",
     "from_name" => "My incredible app!",
     "secure" => "STARTTLS",
     "port" => 465,
 ]);

 * Once you do it on /index.php whenever you need to use EmailSender 
 * just instantiate: $mail = new EmailSender();
 * 
 */


namespace Frameworkitto;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class EmailSender {
    protected $view;
    protected static $config;
    protected static $mailer;

    public function __construct() {
        $this->view = View::getInstance();
        if( !self::$config ) throw new Exception("Email configuration not set");
        $this->mailer = new PHPMailer();

        $smtpSecure = [
            "STARTTLS" => PHPMailer::ENCRYPTION_STARTTLS,
            "SMTPS" => PHPMailer::ENCRYPTION_SMTPS,
        ];

        $this->mailer->isSMTP();
        $this->mailer->Host = self::$config["host"];
        $this->mailer->SMTPAuth = self::$config["auth"];
        $this->mailer->Username = self::$config["username"];
        $this->mailer->Password = self::$config["password"];
        $this->mailer->SMTPSecure = $smtpSecure[ self::$config["secure"] ];

    }

    public static function setConfig($config,Model $spoolModel) {
        self::$config = $config;
        self::$spoolModel = $spoolModel;
    }

    public function templateAssignVariables($variables) {
        $this->view->assignAllVariablesInArray($variables);
    }

    public function sendEmailUsingTemplate($destination,$title,$template,$variables=[],$fromName=null,$replyTo=null) {
        $this->view->assignAllVariablesInArray($variables);
        $content = $this->view->show($template,true);
        return $this->sendEmail($destination,$title,$content,'',$fromName,$replyTo);
    }

    public function sendEmail($destination,$title,$contentHTML, $contentTEXT='',$fromName=null,$replyTo=null) {
        try {
            $fromName = $fromName ? $fromName : self::$config["from_name"];
            $replyTo = $replyTo ? $replyTo : self::$config["from_email"];

            $this->mailer->ClearReplyTos();
            $this->mailer->addReplyTo($replyTo,$fromName);

            $this->mailer->setFrom(self::$config["from_email"], $fromName );
            $this->mailer->addAddress($destination, $destination);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $title;
            $this->mailer->Body    = $contentHTML;
            $this->mailer->AltBody = $contentTEXT;

            $this->mailer->send();

            return(true);
        } catch(\Exception $e) {
            return(false);
        }
    }

}
