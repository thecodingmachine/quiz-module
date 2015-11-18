<?php


namespace TheCodingMachine\Quiz\Model;


class Question
{
    /**
     * The text of the question
     * @var string
     */
    private $questionText;

    /**
     * A list of 4 possible answers.
     * @var string[]
     */
    private $possibleAnswers;

    /**
     * The numeric index of the correct answer
     * @var int
     */
    private $correctAnswer;

    /**
     * Question constructor.
     * @param string $questionText The text of the question
     * @param string[] $possibleAnswers A list of 4 possible answers.
     * @param int $correctAnswer The numeric index of the correct answer
     */
    public function __construct($questionText, array $possibleAnswers, $correctAnswer)
    {
        $this->questionText = $questionText;
        $this->possibleAnswers = $possibleAnswers;
        $this->correctAnswer = $correctAnswer;
    }

    /**
     * @return string
     */
    public function getQuestionText()
    {
        return $this->questionText;
    }

    /**
     * @return \string[]
     */
    public function getPossibleAnswers()
    {
        return $this->possibleAnswers;
    }

    /**
     * @return int
     */
    public function getCorrectAnswer()
    {
        return $this->correctAnswer;
    }
}