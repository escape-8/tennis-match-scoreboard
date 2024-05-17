<?php

declare(strict_types=1);

namespace Test\Validator;

use App\DTO\NewMatchPlayersDTO;
use App\Exception\ValidationException;
use App\Validator\NewMatchPlayersValidator;
use PHPUnit\Framework\TestCase;

class NewMatchPlayersValidatorTest extends TestCase
{
    private NewMatchPlayersValidator $newMatchValidator;
    protected function setUp(): void
    {
        $this->newMatchValidator = new NewMatchPlayersValidator();
    }

    public function testCheckNameSymbolsNoError(): void
    {
        $this->newMatchValidator->checkNameSymbols('player1', 'Samson');
        $this->assertEmpty($this->newMatchValidator->getErrors());
    }

    public function testCheckNameSymbolsNoErrorCyrillic(): void
    {
        $this->newMatchValidator->checkNameSymbols('player1', 'Медведев');
        $this->assertEmpty($this->newMatchValidator->getErrors());
    }

    public function testCheckNameSymbolsError1(): void
    {
        $this->newMatchValidator->checkNameSymbols('player1', 'A.Samson');
        $errors = $this->newMatchValidator->getErrors();
        $this->assertEquals("Name must contains only letters one space between words if there are two words " .
        "and must contain one '-' symbol", $errors['player1']['symbolsName']);
    }

    public function testCheckNameSymbolsError2(): void
    {
        $this->newMatchValidator->checkNameSymbols('player1', '-Samson');
        $errors = $this->newMatchValidator->getErrors();
        $this->assertEquals("Name must contains only letters one space between words if there are two words " .
        "and must contain one '-' symbol", $errors['player1']['symbolsName']);
    }

    public function testCheckNameSymbolsError3(): void
    {
        $this->newMatchValidator->checkNameSymbols('player1', 'Sam#son');
        $errors = $this->newMatchValidator->getErrors();
        $this->assertEquals("Name must contains only letters one space between words if there are two words " .
        "and must contain one '-' symbol", $errors['player1']['symbolsName']);
    }

    public function testStartFromLettersNoError(): void
    {
        $this->newMatchValidator->startFromLetters('player1', 'Samson');
        $this->assertEmpty($this->newMatchValidator->getErrors());
    }

    public function testStartFromLettersError1(): void
    {
        $this->newMatchValidator->startFromLetters('player1', '.1Samson');
        $errors = $this->newMatchValidator->getErrors();
        $this->assertEquals('Name must start with letter', $errors['player1']['startName']);
    }

    public function testStartFromLettersError2(): void
    {
        $this->newMatchValidator->startFromLetters('player1', '-1Samson');
        $errors = $this->newMatchValidator->getErrors();
        $this->assertEquals('Name must start with letter', $errors['player1']['startName']);
    }

    public function testCheckDuplicateNoError(): void
    {
        $data = ['playerName1' => 'Edouard Roger-Vasselin', 'playerName2' => 'Nicolas Barrientos'];
        $this->newMatchValidator->checkDuplicate($data);
        $this->assertEmpty($this->newMatchValidator->getErrors());
    }

    public function testCheckDuplicateError(): void
    {
        $data = ['playerName1' => 'Edouard Roger-Vasselin', 'playerName2' => 'Edouard Roger-Vasselin'];
        $this->newMatchValidator->checkDuplicate($data);
        $errors = $this->newMatchValidator->getErrors();
        $this->assertEquals('Player names must not be duplicated', $errors['playerName2']['duplicate']);
    }

    public function testInputCorrector1(): void
    {
        $result = $this->newMatchValidator->inputCorrector('jean-julie roger');
        $this->assertEquals('Jean-Julie Roger', $result);
    }

    public function testInputCorrector2(): void
    {
        $result = $this->newMatchValidator->inputCorrector('danil-medvedev');
        $this->assertEquals('Danil Medvedev', $result);
    }

    public function testInputCorrectorCyrillic(): void
    {
        $result = $this->newMatchValidator->inputCorrector('даНиил-медвЕдев');
        $this->assertEquals('Даниил Медведев', $result);
    }

    public function testValidate(): void
    {
        $data = ['playerName1' => 'Edouard Roger-Vasselin', 'playerName2' => 'Nicolas Barrientos'];
        $newMatchDTO = $this->newMatchValidator->validate($data);
        $this->assertInstanceOf(NewMatchPlayersDTO::class, $newMatchDTO);
    }

    public function testValidateException(): void
    {
        $data = ['playerName1' => '.dou-ard Roger-Vasselin', 'playerName2' => ' Ni-colas Barrientos'];
        $this->expectException(ValidationException::class);
        $this->newMatchValidator->validate($data);
    }
}
