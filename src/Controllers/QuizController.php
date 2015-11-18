<?php
namespace TheCodingMachine\Quiz\Controllers;

use Mouf\Mvc\Splash\Controllers\Controller;
use Mouf\Html\Template\TemplateInterface;
use Mouf\Html\HtmlElement\HtmlBlock;
use Psr\Log\LoggerInterface;
use TheCodingMachine\Quiz\Model\Quiz;
use \Twig_Environment;
use Mouf\Html\Renderer\Twig\TwigTemplate;
use Mouf\Mvc\Splash\HtmlResponse;

/**
 * This is the controller that manages the Quiz
 */
class QuizController extends Controller {

    /**
     * The logger used by this controller.
     * @var LoggerInterface
     */
    private $logger;

    /**
     * The template used by this controller.
     * @var TemplateInterface
     */
    private $template;

    /**
     * The main content block of the page.
     * @var HtmlBlock
     */
    private $content;

    /**
     * The Twig environment (used to render Twig templates).
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var Quiz[]
     */
    private $quizes;


    /**
     * Controller's constructor.
     * @param LoggerInterface $logger The logger
     * @param TemplateInterface $template The template used by this controller
     * @param HtmlBlock $content The main content block of the page
     * @param Twig_Environment $twig The Twig environment (used to render Twig templates)
     * @param Quiz[] $quizes
     */
    public function __construct(LoggerInterface $logger, TemplateInterface $template, HtmlBlock $content, Twig_Environment $twig, array $quizes) {
        $this->logger = $logger;
        $this->template = $template;
        $this->content = $content;
        $this->twig = $twig;
        $this->quizes = $quizes;
    }

    /**
     * This page displays the list of all available quizes.
     * @URL quiz
     * @Get
     */
    public function index()
    {
        // Let's add the twig file to the template.
        $this->content->addHtmlElement(new TwigTemplate($this->twig, 'vendor/thecodingmachine/quiz-module/views/quiz/index.twig',
            ["quizes" => $this->quizes]));

        $this->template->getWebLibraryManager()->addCssFile('vendor/thecodingmachine/quiz-module/styles/quiz.css');
        return new HtmlResponse($this->template);
    }

    /**
     * @URL question
     * @Get
     * @param int $quiz
     * @param int $question
     * @param array $previousAnswers
     * @return HtmlResponse
     */
    public function quiz($quiz, $question = 0, $previousAnswers = [])
    {
        $quizObj = $this->quizes[$quiz];
        $questionObj = $quizObj->getQuestion($question);

        // Let's add the twig file to the template.
        $this->content->addHtmlElement(new TwigTemplate($this->twig, 'vendor/thecodingmachine/quiz-module/views/quiz/question.twig', [
            "quizIndex" => $quiz,
            "quiz" => $quizObj,
            "questionIndex" => $question,
            "question" => $questionObj,
            "previousAnswers" => $previousAnswers
        ]));

        $this->template->getWebLibraryManager()->addCssFile('vendor/thecodingmachine/quiz-module/styles/quiz.css');
        return new HtmlResponse($this->template);
    }

    /**
     * @URL quiz-answer
     * @Post
     * @param int $quiz
     * @param int $question
     * @param int $answer
     * @param array $previousAnswers
     * @return HtmlResponse
     */
    public function answer($quiz, $question, $answer, $previousAnswers = [])
    {
        $quizObj = $this->quizes[$quiz];
        $questionObj = $quizObj->getQuestion($question);

        if ($questionObj->getCorrectAnswer() === $answer) {
            $success = true;
        } else {
            $success = false;
        }

        // Let's add the answer to the list of previous answers.
        $previousAnswers[$question] = $answer;

        // Let's add the twig file to the template.
        $this->content->addHtmlElement(new TwigTemplate($this->twig, 'vendor/thecodingmachine/quiz-module/views/quiz/answer.twig', [
            "success" => $success,
            "quizIndex" => $quiz,
            "quiz" => $quizObj,
            "questionIndex" => $question,
            "question" => $questionObj,
            "previousAnswers" => $previousAnswers
        ]));

        $this->template->getWebLibraryManager()->addCssFile('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
        $this->template->getWebLibraryManager()->addCssFile('vendor/thecodingmachine/quiz-module/styles/quiz.css');
        return new HtmlResponse($this->template);
    }

    /**
     * @URL results
     * @GET
     * @param int $quiz
     * @param array $previousAnswers
     * @return HtmlResponse
     */
    public function results($quiz, array $previousAnswers = [])
    {
        $quizObj = $this->quizes[$quiz];

        $nbCorrectAnswers = 0;

        foreach ($quizObj->getQuestions() as $index => $question) {
            if ($question->getCorrectAnswer() === $previousAnswers[$index]) {
                $nbCorrectAnswers++;
            }
        }

        // Let's add the twig file to the template.
        $this->content->addHtmlElement(new TwigTemplate($this->twig, 'vendor/thecodingmachine/quiz-module/views/quiz/results.twig', [
            "nbCorrectAnswers" => $nbCorrectAnswers,
            "quizIndex" => $quiz,
            "quiz" => $quizObj,
            "previousAnswers" => $previousAnswers
        ]));

        $this->template->getWebLibraryManager()->addCssFile('vendor/thecodingmachine/quiz-module/styles/quiz.css');
        return new HtmlResponse($this->template);
    }
}
