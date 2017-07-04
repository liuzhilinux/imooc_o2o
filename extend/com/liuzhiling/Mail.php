<?php
/**
* 邮件发送类。
* 依赖于 PHPMailer 模块。
* 通过 composer require phpmailer/phpmailer 安装。
* 要使用该类库，必须设置相关配置于 application/config.php
*
*
*/
namespace com\liuzhiling;


class Mail{

	// 实例化的单例对象。
	private static $instance = NULL;

	private $mailer;

	/**
	* 构造器。
	*/

	private function __construct($config){
		// 加载 PHPMailer 类库。
		if(!vendor('phpmailer/phpmailer/PHPMailerAutoload')) 
			throw new \think\Exception('PHPMailerAutoload NOT find!', 100006);
		PHPMailerAutoload('phpmailer');
		// 初始化 PHPMailer 类。
		if(is_null($this->mailer = new \PHPMailer()))
			throw new \think\Exception('PHPMailer NOT find!', 100006);
		// 配置 PHPMailer 类。
		//$this->mailer->SMTPDebug = 3;							// Enable verbose debug output
		$this->mailer->isSMTP();								// Set mailer to use SMTP
		$this->mailer->Host = $config['host'];					// Specify main and backup SMTP servers
		$this->mailer->SMTPAuth = true;							// Enable SMTP authentication
		$this->mailer->Username = $config['username'];			// SMTP username
		$this->mailer->Password = $config['password'];			// SMTP password
		$this->mailer->SMTPSecure = 'tls';						// Enable TLS encryption, `ssl` also accepted
		$this->mailer->Port = $config['port'];					// TCP port to connect to
	}


	/**
	* 初始化。
	* 返回一个单例对象。
	* @param array $config 
	* 配置数组包含键值对：
	*        'host'      => 主机名
	*        'username'  => 用户名
	*        'password'  => 密码
	*        'port'      => 端口
	* @return object Mail 对象。
	*
	*/
	public static function getInstance($config){
		if(is_null(self::$instance)){
			self::$instance = new self($config);
		}
		return self::$instance;
	}

	/**
	* 设置来源。
	* @param array $data
	* 来源信息。
	*        'my_email'
	*        'my_name'
	*        'to'
	*        'to_name'
	*        'reply_to'
	*        'reply_to_name'
	*        'cc'
	*        'bcc'
	*/
	public function setFrom($data){
		$this->mailer->setFrom($data['my_email'], $data['my_name']);
		$this->mailer->addAddress($data['to'], $data['to_name']);     // Add a recipient
		$this->mailer->addReplyTo($data['reply_to'], $data['reply_to_name']);
		$this->mailer->addCC($data['cc']);
		$this->mailer->addBCC($data['bcc']);
	}

	/**
	* 添加附件。
	* @param string $path
	* @param string $file_name
	*/
	public function addAttachment($path, $file_name){
		$this->mailer->addAttachment($path, $file_name);
	}

	/**
	* 发送邮件。
	* @param string $subject 标题
	* @param string $body 正文
	* @param boolean $html_support 是否支持 HTML。
	* @return TURE if success and a error message if failure.
	*/
	public function send($subject, $body, $html_support = TRUE){
		$this->mailer->Subject = $subject;
		if($html_support) $this->mailer->Body = $body;
		else $this->mailer->AltBody = $body;
		if(!$this->mailer->send()) 
			return 'Message could not be sent.Mailer Error: ' . $this->mailer->ErrorInfo;
		return TRUE;
	}


}