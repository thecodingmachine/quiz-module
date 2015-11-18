<?php


namespace TheCodingMachine\Quiz\Model;

/**
 * An object representing a Quiz
 */
class Quiz
{
    /**
     * The name of the Quiz
     * @var string
     */
    private $name;

    /**
     * The questions of the Quiz
     * @var Question[]
     */
    private $questions;

    /**
     * @param string $name The name of the Quiz
     * @param Question[] $questions The questions of the Quiz
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