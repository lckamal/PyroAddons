<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Contact module
 *
 * @author  PyroCMS Dev Team
 * @author	Tony G. Bola�o
 * @package PyroCMS\Core\Modules\Contact
 */
class Module_Contact extends Module
{

	public $version = '1.0.1';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Contact',
				'ar' => 'الإتصال',
				'br' => 'Contato',
				'pt' => 'Contacto',
				'cs' => 'Kontakt',
				'da' => 'Kontakt',
				'de' => 'Kontakt',
				'el' => 'Επικοινωνία',
				'es' => 'Contacto',
                            'fa' => 'تماس با ما',
				'fi' => 'Ota yhteyttä',
				'fr' => 'Contact',
				'he' => 'יצירת קשר',
				'id' => 'Kontak',
				'it' => 'Contattaci',
				'lt' => 'Kontaktinė formą',
				'nl' => 'Contact',
				'pl' => 'Kontakt',
				'ru' => 'Обратная связь',
				'sl' => 'Kontakt',
				'tw' => '聯絡我們',
				'cn' => '联络我们',
				'hu' => 'Kapcsolat',
				'th' => 'ติดต่อ',
				'se' => 'Kontakt'
			),
			'description' => array(
				'en' => 'Adds a form to your site that allows visitors to send emails to you without disclosing an email address to them.',
				'ar' => 'إضافة استمارة إلى موقعك تُمكّن الزوّار من مراسلتك دون علمهم بعنوان البريد الإلكتروني.',
				'br' => 'Adiciona um formulário para o seu site permitir aos visitantes que enviem e-mails para voce sem divulgar um endereço de e-mail para eles.',
				'pt' => 'Adiciona um formulário ao seu site que permite aos visitantes enviarem e-mails sem divulgar um endereço de e-mail.',
				'cs' => 'Přidá na web kontaktní formulář pro návštěvníky a uživatele, díky kterému vás mohou kontaktovat i bez znalosti vaší e-mailové adresy.',
				'da' => 'Tilføjer en formular på din side som tillader besøgende at sende mails til dig, uden at du skal opgive din email-adresse',
				'de' => 'Fügt ein Formular hinzu, welches Besuchern erlaubt Emails zu schreiben, ohne die Kontakt Email-Adresse offen zu legen.',
				'el' => 'Προσθέτει μια φόρμα στον ιστότοπό σας που επιτρέπει σε επισκέπτες να σας στέλνουν μηνύμα μέσω email χωρίς να τους αποκαλύπτεται η διεύθυνση του email σας.',
                            'fa' => 'فرم تماس را به سایت اضافه می کند تا مراجعین بتوانند بدون اینکه ایمیل شما را بدانند برای شما پیغام هایی را از طریق ایمیل ارسال نمایند.',
				'es' => 'Añade un formulario a tu sitio que permitirá a los visitantes enviarte correos electrónicos a ti sin darles tu dirección de correo directamente a ellos.',
				'fi' => 'Luo lomakkeen sivustollesi, josta kävijät voivat lähettää sähköpostia tietämättä vastaanottajan sähköpostiosoitetta.',
				'fr' => 'Ajoute un formulaire à votre site qui permet aux visiteurs de vous envoyer un e-mail sans révéler votre adresse e-mail.',
				'he' => 'מוסיף תופס יצירת קשר לאתר על מנת לא לחסוף כתובת דואר האלקטרוני של האתר למנועי פרסומות',
				'id' => 'Menambahkan formulir ke dalam situs Anda yang memungkinkan pengunjung untuk mengirimkan email kepada Anda tanpa memberikan alamat email kepada mereka',
				'it' => 'Aggiunge un modulo al tuo sito che permette ai visitatori di inviarti email senza mostrare loro il tuo indirizzo email.',
				'lt' => 'Prideda jūsų puslapyje formą leidžianti lankytojams siūsti jums el. laiškus neatskleidžiant jūsų el. pašto adreso.',
				'nl' => 'Voegt een formulier aan de site toe waarmee bezoekers een email kunnen sturen, zonder dat u ze een emailadres hoeft te tonen.',
				'pl' => 'Dodaje formularz kontaktowy do Twojej strony, który pozwala użytkownikom wysłanie maila za pomocą formularza kontaktowego.',
				'ru' => 'Добавляет форму обратной связи на сайт, через которую посетители могут отправлять вам письма, при этом адрес Email остаётся скрыт.',
				'sl' => 'Dodaj obrazec za kontakt da vam lahko obiskovalci pošljejo sporočilo brez da bi jim razkrili vaš email naslov.',
				'tw' => '為您的網站新增「聯絡我們」的功能，對訪客是較為清楚便捷的聯絡方式，也無須您將電子郵件公開在網站上。',
				'cn' => '为您的网站新增“联络我们”的功能，对访客是较为清楚便捷的联络方式，也无须您将电子邮件公开在网站上。',
				'th' => 'เพิ่มแบบฟอร์มในเว็บไซต์ของคุณ ช่วยให้ผู้เยี่ยมชมสามารถส่งอีเมลถึงคุณโดยไม่ต้องเปิดเผยที่อยู่อีเมลของพวกเขา',
				'hu' => 'Létrehozható vele olyan űrlap, amely lehetővé teszi a látogatók számára, hogy e-mailt küldjenek neked úgy, hogy nem feded fel az e-mail címedet.',
				'se' => 'Lägger till ett kontaktformulär till din webbplats.'
			),
			'frontend' => false,
			'backend' => true,
            'menu' => 'data',
            'sections' => array(
                'contactlog' => array(
                    'name' => 'contact_showlog_section',
                    'uri' => 'admin/contact',
                ),
            )
		);
	}

	public function install()
	{
		$this->dbforge->drop_table('contact_log');

		$tables = array(
			'contact_log' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true,),
				'email' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => '',),
				'subject' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => '',),
				'message' => array('type' => 'TEXT',),
				'sender_agent' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => '',),
				'sender_ip' => array('type' => 'VARCHAR', 'constraint' => 45, 'default' => '',),
				'sender_os' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => '',),
				'sent_at' => array('type' => 'INT', 'constraint' => 11, 'default' => 0,),
				'attachments' => array('type' => 'TEXT',),
			),
		);

		if ( ! $this->install_tables($tables))
		{
			return false;
		}

		return true;
	}

	public function uninstall()
	{
		// This is a core module, lets keep it around.
		return false;
	}

	public function upgrade($old_version)
	{
		return true;
	}

}