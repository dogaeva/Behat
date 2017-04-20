<?php
	
	use Behat\Behat\Tester\Exception\PendingException;
	use Behat\Behat\Context\Context;
	use Behat\Gherkin\Node\PyStringNode;
	use Behat\Gherkin\Node\TableNode,
    Behat\Behat\Hook\Scope\AfterScenarioScope,
	Behat\Behat\Hook\Scope\BeforeScenarioScope,
	Facebook\WebDriver\Remote\DesiredCapabilities,
	Facebook\WebDriver\Remote\RemoteWebDriver,
	Facebook\WebDriver\WebDriverBy,
	Facebook\WebDriver,
	Facebook\WebDriver\WebDriverSelect;
	
	/**
		* Defines application features from the specific context.
	*/
	class FeatureContext implements Context
	{   /**
		* @var \RemoteWebDriver
	*/
	protected $webDriver;
	protected $baseURL;
	
    /**
		* Initializes context.
		*
		* Every scenario gets its own context instance.
		* You can also pass arbitrary arguments to the
		* context constructor through behat.yml.
	*/
    public function __construct()
    {
		$this->baseURL = "https://spb.hh.ru/";
	}
	
    /**
		* @Then I create CV
	*/
    public function iCreateCV()
    {   $forms = array(
		"phone.formatted" => "9634567891",
		"primaryEducation.name" => "ИТМО",
		"primaryEducation.organization" => "ПИиКТ",
		"primaryEducation.result" => "Программная инженерия",
		);
		
		$checkbox = array(
		
		"expirience" => "Нет опыта работы",
		"special1" => "Информационные технологии, Интернет, Мультимедиа",
		"special2" => "Маркетинг, Реклама, PR",
		"special3" => "Искусство, Развлечения, Масс-медиа",
		);
		$options = array(
		
		"HH-Resume-Birthday-Day" => "05",
		"HH-Resume-Birthday-Month" => "10",
		"HH-Resume-Birthday-Year" => "1996",
		"HH-Resume-Education-Year" => "2019",
		"HH-Resume-Languages-Language-Degree-Select" => "basic",
		);
        
		
		$this->webDriver->findElement(WebDriverBy::linkText("Разместить резюме"))->click();
		
		//Пол
		$element = $this->webDriver->findElement(WebDriverBy::className("HH-Resume-Gender-Value"));
		$element = $this->webDriver->findElement(WebDriverBy::className("bloko-radio__text"))->click();;
		
		//Заполнение форм
		foreach ($forms as $input => $value) {
			$this->webDriver->findElement(WebDriverBy::name($input))->sendKeys($value);
		}
		
		//Заполнение чекбоксов
		foreach ($checkbox as $input => $value) {
			$this->webDriver->findElement(WebDriverBy::xpath("//span[text()='$value']"))->click();;
		}
		
		//Выбор из вариантов
		foreach ($options as $input => $value) {
			$option = $this->webDriver->findElement(WebDriverBy::className($input));
			$option = new WebDriverSelect($option);
			$option->selectByValue($value);
		}
		
		//Подтверждение
		$this->webDriver->findElement(WebDriverBy::className("HH-Resume-Form-Submit"))->click();
		$this->webDriver->wait(10,100);
		
	}
	
	/**
		* @Then I delete CV
	*/
    public function iDeleteCV()
    {   //Удаление резюме
       	$this->webDriver->findElement(WebDriverBy::linkText("Посмотреть резюме"))->click();
		$this->webDriver->findElement(WebDriverBy::className("HH-Resume-DeleteButton"))->click();
		$this->webDriver->switchTo()->alert()->accept();
	}
	
    /**
		* @When I load a homepage
	*/
    public function iLoadAHomepage()
    {
        $this->webDriver->get($this->baseURL,"/");
	}
	
	/**
		* @When I login
	*/
    public function iLogin()
    {  
		$loginform = array(
		"username" => "samp@sample.ru",
		"password" => "123qwe",
		);
		
		//Заполнение логина и пароля
	    $this->webDriver->get($this->baseURL,"/");
		$form = $this->webDriver->findElement(WebDriverBy::className('login-form'));
		foreach ($loginform as $input => $value) {
			$form->findElement(WebDriverBy::name($input))->sendKeys($value);
			
		}
		$form->submit();
		
	}
	
	/**
		* @BeforeScenario
	*/
    public function openWebBrouser(BeforeScenarioScope $event)
    {
		$capabilities = DesiredCapabilities::chrome();
		$this->webDriver = RemoteWebDriver::create('127.0.0.1:4444/wd/hub',$capabilities);
		
	}
	
    /**
		* @AfterScenario
	*/
    public function closeWebDriver(AfterScenarioScope $event)
    {
		if($this->webDriver) $this->webDriver->quit();
	}
	}
