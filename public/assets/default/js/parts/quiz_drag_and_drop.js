$(document).ready(function() {
    var quizQuestions = JSON.parse($('#quizQuestions').val());
    var passMark = parseInt($('#passMark').val());
    var selectedQuestion = null;
    var selectedAnswer = null;
    var correctAnswersCount = 0; // Counter for correct answers

    $(".question-translation").click(function() {
        if ($(this).hasClass('correct-answer')) {
            return; 
        }

        selectedQuestion = $(this).data('id');
        $(this).addClass("selected-question");
    });

    $(".answer-list .card").click(function() {
        if ($(".question-translation[data-id='" + selectedQuestion + "']").hasClass('correct-answer')) {
            return; 
        }

        selectedAnswer = $(this).data('id');
        var currentSelectedQuestion = selectedQuestion;
        var currentSelectedAnswer = selectedAnswer;

        if (selectedQuestion !== null && selectedAnswer !== null) {
            var question = quizQuestions.find(function(question) {
                return question.translations.some(function(translation) {
                    return translation.id === selectedQuestion && translation.correct !== null;
                });
            });
            var correctAnswer = question.quizzes_questions_answers.find(function(answer) {
                return answer.id === selectedAnswer;
            });
            if (correctAnswer && correctAnswer.title === question.translations.find(translation => translation.id === selectedQuestion).correct) {
                
                $(".question-translation[data-id='" + selectedQuestion + "']").addClass('correct-answer').off('click');
                $(this).addClass('correct-answer').off('click');
                correctAnswersCount++; // Increment correct answers count
            } else {
                $(this).addClass('incorrect-answer');
                setTimeout(function() {
                    $(".answer-list .card[data-id='" + currentSelectedAnswer + "']").removeClass('incorrect-answer');
                }, 3000);
                setTimeout(function() {
                    $(".question-translation[data-id='" + currentSelectedQuestion + "']").removeClass('selected-question');
                }, 3000);
            }
            selectedQuestion = null;
            selectedAnswer = null;

            // Check if all questions and answers are correct
            
            if (question && question.translations && Array.isArray(question.translations)) {
                var queslength = question.translations.length;
                if(queslength === correctAnswersCount)
                {
                    
                    var passMark = parseInt($('.passMark').text());
                    passMark += question.grade; // Add the grade of the current question
                    $('.passMark').text(`${question.grade}\/ ${question.grade}`); 
                    
                    console.log($('#passMark').val(question.grade))
                }
           
            }

        }
    });
});
