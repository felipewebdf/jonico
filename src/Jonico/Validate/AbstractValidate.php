<?php
namespace Jonico\Validate;

use Zend\I18n\Translator\Translator;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;
use Zend\Validator\StringLength;
use Zend\Validator\NotEmpty;
use Zend\Mvc\I18n\Translator as TranslatorValidator;
use Zend\I18n\Translator\Loader\PhpArray;

/**
 * Description of AbstractValidate
 *
 * @author fsilva
 */
class AbstractValidate
{
    /**
     *
     * @var array
     */
    protected $inputs;
    protected $validators;
    protected $messages;
    protected $translator;

    public function __construct()
    {
        $translator = new Translator();
        $translator->addTranslationFile(
            PhpArray::class,
            __DIR__.'/../../../../../zendframework/zend-i18n-resources/languages/pt_BR/Zend_Validate.php', 'default', 'pt_BR'
        );
        $translator->setLocale('pt_BR');
        $this->translator = new TranslatorValidator($translator);
    }

    /**
     * @param string $inputName
     */
    public function setEmail($inputName)
    {
        $email = new EmailAddress();
        $email->setTranslator($this->translator);
        $this->validators[$inputName]['mail'] = $email;
    }
    /**
     * @param string $inputName
     * @param integer $max
     * @param integer $min default 0
     * @return StringLength
     */
    public function setStringLength($inputName, $max, $min=0)
    {
        $stringLength = new StringLength([
            'max' => $max,
            'min' => $min
        ]);
        $stringLength->setTranslator($this->translator);
        $this->validators[$inputName]['stringLength'] = $stringLength;
    }
    /**
     * @param string $inputName
     * @return NotEmpty
     */
    public function setNotEmpty($inputName)
    {
        $notEmpty = new NotEmpty();
        $notEmpty->setTranslator($this->translator);
        $this->validators[$inputName]['notEmpty'] = $notEmpty;
    }

    /**
     * Add validator
     * @param string $inputName
     */
    protected function addInputValidator($inputName)
    {
        if (!isset($this->validators[$inputName])) {
            return;
        }
        $input = new Input($inputName);
        foreach($this->validators[$inputName] as $validator) {
            $input->getValidatorChain()->attach($validator);
        }
        $this->inputs[$inputName] = $input;
    }
    /**
     * Populate input filters params and validators
     * @param array $arrParams
     * @return InputFilter
     */
    protected function populateInputFilter($arrParams)
    {
        $inputFilter = new InputFilter();
        foreach($this->inputs as $input) {
            $inputFilter->add($input);
        }
        $inputFilter->setData((array)$arrParams);
        return $inputFilter;
    }
    /**
     *
     * @param array $arrParams
     * @return boolean
     */
    public function isValid($arrParams)
    {
        foreach($arrParams as $inputName=>$value) {
            $this->addInputValidator($inputName);
        }
        $inputFilter = $this->populateInputFilter((array)$arrParams);
        if ($inputFilter->isValid()) {
            return true;
        }
        $this->messages = $inputFilter->getInvalidInput();
        return false;
    }
    /**
     *
     * @return json
     */
    public function getJsonMessages()
    {
        $errors = [];
        foreach ($this->messages as $error) {
            $errors[$error->getName()][] = $error->getMessages();
        }
        return json_encode(['validation_messages' => $errors], true);
    }
}
