<?php

declare(strict_types=1);

namespace App\Validator;

use App\DTO\NewMatchPlayersDTO;
use App\Exception\ValidationException;

class NewMatchPlayersValidator
{
    /**
     * @var array<string, array<string, string>> $errors
     */
    private array $errors = [];

    /**
     * @return array<string, array<string, string>>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array<string> $data
     * @return NewMatchPlayersDTO
     */
    public function validate(array $data): NewMatchPlayersDTO
    {
        $this->checkDuplicate($data);

        foreach ($data as $playerField => $playerName) {
            $this->startFromLetters($playerField, $playerName);
            $this->checkNameSymbols($playerField, $playerName);
        }

        if (count($this->errors) > 0) {
            throw new ValidationException('html/pages/new-match.html.twig', ['errors' => $this->errors, ...$data]);
        }

        return new NewMatchPlayersDTO(
            $this->inputCorrector($data['playerName1']),
            $this->inputCorrector($data['playerName2'])
        );
    }

    public function checkNameSymbols(string $playerField, string $name): void
    {
        if (!preg_match('/^[[:alpha:]]+(?:-[[:alpha:]]+)?(?: [[:alpha:]]+(?:-[[:alpha:]]+)?)?$/mu', $name)) {
            $this->errors[$playerField]['symbolsName'] = "Name must contains only letters one space between words if " .
            "there are two words and must contain one '-' symbol";
        }
    }

    public function startFromLetters(string $playerField, string $name): void
    {
        if (preg_match('/^\W|^\d/mu', $name)) {
            $this->errors[$playerField]['startName'] = 'Name must start with letter';
        }
    }

    /**
     * @param array<string> $data
     * @return void
     */
    public function checkDuplicate(array $data): void
    {
        $duplicates = [];

        foreach ($data as $playerField => $playerName) {
            $correctName = $this->inputCorrector($playerName);
            if (in_array($correctName, $duplicates)) {
                $this->errors[$playerField]['duplicate'] = 'Player names must not be duplicated';
            }
            $duplicates[] = $correctName;
        }
    }

    public function inputCorrector(string $name): string
    {
        $words = explode(' ', str_replace('-', ' ', $name));

        if (count($words) === 2) {
            $name = implode(' ', $words);
        }

        $nameUpper = $this->mbUpperCaseWords(mb_strtolower($name));
        $nameChangeLine =  str_replace('-', '- ', $nameUpper);
        $nameUpperLine = $this->mbUpperCaseWords(mb_strtolower($nameChangeLine));

        return str_replace('- ', '-', $nameUpperLine);
    }

    private function mbUpperCaseWords(string $string): string
    {
        return mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
    }
}
