<?php


namespace TheCodingMachine\Quizz\Model;

/**
 * An object representing a Quizz
 */
class Quizz
{
    /**
     * The name of the Quizz
     * @var string
     */
    private $name;

    /**
     * The questions of the Quizz
     * @var Question[]
     */
    private $questions;

    /**
     * @param string $name The name of the Quizz
     * @param Question[] $questions The questions of the Quizz
     */
    public function __construct($name, array $questions)
    {
        $this->name = $name;
        $this->questions = $questions;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Question[]
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param int $index
     * @return Question
     */
    public function getQuestion($index) {
        return $this->questions[$index];
    }

}