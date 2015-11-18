<?php
namespace TheCodingMachine\Quizz\Controllers;

use Mouf\Mvc\Splash\Controllers\Controller;
use Mouf\Html\Template\TemplateInterface;
use Mouf\Html\HtmlElement\HtmlBlock;
use Psr\Log\LoggerInterface;
use TheCodingMachine\Quizz\Model\Quizz;
use \Twig_Environment;
use Mouf\Html\Renderer\Twig\TwigTemplate;
use Mouf\Mvc\Splash\HtmlResponse;

/**
 * This is the controller that manages the Quizz
 */
class QuizzController extends Controller {

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
     * @var Quizz[]
     */
    private $quizzes;


    /**
     * Controller's constructor.
     * @param LoggerInterface $logger The logger
     * @param TemplateInterface $template The template used by this controller
     * @param HtmlBlock $content The main content block of the page
     * @param Twig_Environment $twig The Twig environment (used to render Twig templates)
     * @param Quizz[] $quizzes
     */
    public function __construct(LoggerInterface $logger, TemplateInterface $template, HtmlBlock $content, Twig_Environment $twig, array $quizzes) {
        $this->logger = $logger;
        $this->template = $template;
        $this->content = $content;
        $this->twig = $twig;
        $this->quizzes = $quizzes;
    }

    /**
     * This page displays the list of all available quizzes.
     * @URL quizz
     * @Get
     */
    public function index()
    {
        // Let's add the twig file to the template.
        $this->content->addHtmlElement(new TwigTemplate($this->twig, 'vendor/thecodingmachine/quizz-module/views/quizz/index.twig',
            ["quizzes" => $this->quizzes]));

        $this->template->getWebLibraryManager()->addCssFile('vendor/thecodingmachine/quizz-module/styles/quiz.css');
        return new HtmlResponse($this->template);
    }

    /**
     * @URL question
     * @Get
     * @param int $quizz
     * @param int $question
     * @param array $previousAnswers
     * @return HtmlResponse
     */
    public function quizz($quizz, $question = 0, $previousAnswers = [])
    {
        $quizzObj = $this->quizzes[$quizz];
        $questionObj = $quizzObj->getQuestion($question);

        // Let's add the twig file to the template.
        $this->content->addHtmlElement(new TwigTemplate($this->twig, 'vendor/thecodingmachine/quizz-module/views/quizz/question.twig', [
            "quizzIndex" => $quizz,
            "quizz" => $quizzObj,
            "questionIndex" => $question,
            "question" => $questionObj,
            "previousAnswers" => $previousAnswers
        ]));

        $this->template->getWebLibraryManager()->addCssFile('vendor/thecodingmachine/quizz-module/styles/quiz.css');
        return new HtmlResponse($this->template);
    }

    /**
     * @URL quizz-answer
     * @Post
     * @param int $quizz
     * @param int $question
     * @param int $answer
     * @param array $previousAnswers
     * @return HtmlResponse
     */
    public function answer($quizz, $question, $answer, $previousAnswers = [])
    {
        $quizzObj = $this->quizzes[$quizz];
        $questionObj = $quizzObj->getQuestion($question);

        if ($questionObj->getCorrectAnswer() === $answer) {
            $success = true;
        } else {
            $success = false;
        }

        // Let's add the answer to the list of previous answers.
        $previousAnswers[$question] = $answer;

        // Let's add the twig file to the template.
        $this->content->addHtmlElement(new TwigTemplate($this->twig, 'vendor/thecodingmachine/quizz-module/views/quizz/answer.twig', [
            "success" => $success,
            "quizzIndex" => $quizz,
            "quizz" => $quizzObj,
            "questionIndex" => $question,
            "question" => $questionObj,
            "previousAnswers" => $previousAnswers
        ]));

        $this->template->getWebLibraryManager()->addCssFile('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
        $this->template->getWebLibraryManager()->addCssFile('vendor/thecodingmachine/quizz-module/styles/quiz.css');
        return new HtmlResponse($this->template);
    }

    /**
     * @URL results
     * @GET
     * @param int $quizz
     * @param array $previousAnswers
     * @return HtmlResponse
     */
    public function results($quizz, array $previousAnswers = [])
    {
        $quizzObj = $this->quizzes[$quizz];

        $nbCorrectAnswers = 0;

        foreach ($quizzObj->getQuestions() as $index => $question) {
            if ($question->getCorrectAnswer() === $previousAnswers[$index]) {
                $nbCorrectAnswers++;
            }
        }

        // Let's add the twig file to the template.
        $this->content->addHtmlElement(new TwigTemplate($this->twig, 'vendor/thecodingmachine/quizz-module/views/quizz/results.twig', [
            "nbCorrectAnswers" => $nbCorrectAnswers,
            "quizzIndex" => $quizz,
            "quizz" => $quizzObj,
            "previousAnswers" => $previousAnswers
        ]));

        $this->template->getWebLibraryManager()->addCssFile('vendor/thecodingmachine/quizz-module/styles/quiz.css');
        return new HtmlResponse($this->template);
    }
}
