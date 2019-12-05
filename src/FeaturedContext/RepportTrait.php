<?php
namespace froggdev\BehatContexts\FeaturedContext;

require( __DIR__ . '/../Tool/PHPMailer/PHPMailer.php');
require( __DIR__ . '/../Tool/PHPMailer/SMTP.php');
require( __DIR__ . '/../Tool/PHPMailer/Exception.php');

use PHPMailer\PHPMailer;
use froggdev\BehatContexts\Tool\HZip;
use froggdev\BehatContexts\Config;

trait RepportTrait
{

    /**
     * @Given J'efface les anciens rapports behat
     */
    public function iCleanOldRepport(): void
    {
        $this->delTree( Config::REPORT_PATH );
    }

    /**
     * @throws \Exception
     */
    public function ISaveTheRepport(): void
    {
			if($this->exportPath){

        // create main folder
        $currentDate = new \DateTime();
        $currentRepport = $currentDate->format('Y-m-d');
        $fullPath = $this->exportPath.'/'.$currentRepport. '/' . $currentDate->format('His') . '_' . $this->getMinkParameter('files_path') . '-' . $this->getMinkParameter('browser_name');

        // Create files
        mkdir($fullPath,0777 , true);

        // Copy folders
        self::copyr( $this->reportPath ,$fullPath );

        // Get main script path
        $mainPath=str_replace('/features/bootstrap/FeaturedContext/', "",__DIR__);
        //read the entire string
        $str=file_get_contents($fullPath.'/index.html');
        //remove the file path
        $str=str_replace('file://' . $mainPath . $this->exportPath, "",$str);
        //write the entire string
        file_put_contents($fullPath.'/index.html', $str);

        // Zip + Delete file if no errors
        if( !$this->hasError ) {
            // Zip the files
            HZip::zipDir($fullPath,  $fullPath.'.zip');
            // Clean folder
            $this->delTree($fullPath );
        }
			}

			$this->ISendMail(!$this->hasError);

			$this->iCleanOldRepport();
    }

    /**
     * @Given J'envoie un mail
     */
    public function ISendMail(bool $ok=true):void
    {	
			if(!$this->doMail) return;


        try {
            $mail = new PHPMailer\PHPMailer();
            $mail->IsSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host = $this->smtp; 			// SMTP server example
            $mail->SMTPDebug = 0;           // enables SMTP debug information (for testing)
            $mail->SMTPAuth = false;        // enable SMTP authentication
            $mail->Port = $this->smtpPort;  // set the SMTP port for the GMAIL server
            $mail->SMTPDebug = 0;

            //Recipients
            $mail->setFrom($this->mailFrom);
            $mail->addAddress($this->mailTo);
						/*
            $mail->setFrom('Behat-test@uniprevoyance.fr', 'Behat-test');
            $mail->addAddress('Remy.MARSIGLIETTI-prestataire@uniprevoyance.fr', 'MARSIGLIETTI Remy');     // Add a recipient
						*/
            // Test if is allowed to send email
            /*
            $adressesTo = $mail->getToAddresses();
            foreach($adressesTo as $adresseTo){
                if( ! (strpos($adresseTo,'@uniprevoyance.fr') || strpos($adresseTo,'@uniprevoyance.intraxa')) ){
                    throw new \Exception('Message could not be sent, address is not allowed : ' . $adresseTo);
                }
            }
            */

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = ($ok ? '[OK]' : '[ERREUR]') . ' Résultat des tests fonctionnels BeHat';
						
            $mail->Body = 'Le rapport est disponible à l\'adresse suivant : ' .  ($this->exportPath ? $this->exportPath : $this->reportPath);
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if(!$mail->Send()) {
                throw new \Exception('Message could not be sent : ' . $mail->ErrorInfo);
            }
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }
    }
}