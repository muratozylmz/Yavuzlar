const questionContainer = document.getElementById("soru-konteyner");
const questionElement = document.getElementById("soru");
const answerButtons = document.getElementById("cevap");
const nextButton = document.getElementById("sonraki");
const restartButton = document.getElementById("yeniden");
const resultDiv = document.getElementById("result");

let shuffledQuestions, currentQuestionIndex, score;
let questions = [];

if (localStorage.getItem("sorular")) {
    const storedQuestions = JSON.parse(localStorage.getItem("sorular"));
    questions = storedQuestions.map(soru => {
        return {
            question: soru.text,
            answers: [
                { text: soru.cevaplar.cevap1, correct: soru.dogruCevap === "cevap1" },
                { text: soru.cevaplar.cevap2, correct: soru.dogruCevap === "cevap2" },
                { text: soru.cevaplar.cevap3, correct: soru.dogruCevap === "cevap3" },
                { text: soru.cevaplar.cevap4, correct: soru.dogruCevap === "cevap4" }
            ]
        };
    });
}


startQuiz();

function startQuiz() {
    score = 0;
    questionContainer.style.display = "flex";
    shuffledQuestions = questions.sort(() => Math.random() - 0.5);
    currentQuestionIndex = 0;
    nextButton.classList.remove("hide");
    restartButton.classList.add("hide");
    resultDiv.classList.add("hide");
    setNextQuestion();
}

function setNextQuestion() {
    resetState();
    showQuestion(shuffledQuestions[currentQuestionIndex]);
}

function showQuestion(question) {
    questionElement.innerText = question.question;
    question.answers.forEach((answer,index) => {
        const inputGroup = document.createElement("div");
        inputGroup.classList.add("input-group");

        const radio = document.createElement("input");
        radio.type ="radio";
        radio.id = "answer" + index;
        radio.name = "answer";
        radio.value = index;
        
        const label = document.createElement("label");
        label.htmlFor = "answer" +index;
        label.innerText = answer.text;

        inputGroup.appendChild(radio);
        inputGroup.appendChild(label);
        answerButtons.appendChild(inputGroup);
    });

}

function resetState() {
    while (answerButtons.firstChild) {
        answerButtons.removeChild(answerButtons.firstChild);
    }
}

nextButton.addEventListener("click", () => {
    const answerIndex = Array.from(
        answerButtons.querySelectorAll("input")
    ).findIndex((radio) => radio.checked);
    if (answerIndex !== -1) {
        if(shuffledQuestions[currentQuestionIndex].answers[answerIndex].correct) {
            score++;
        }
        currentQuestionIndex++;
        if (shuffledQuestions.length > currentQuestionIndex) {
            setNextQuestion();
        }else {
            endQuiz();
        } 
    }else {
        alert("Please select an answer");
    }
    
});

restartButton.addEventListener("click", startQuiz);

function endQuiz() {
    questionContainer.style.display = "none";
    nextButton.classList.add("hide");
    restartButton.classList.remove("hide");
    resultDiv.classList.remove("hide");
    resultDiv.innerText = `Toplam skorun: ${score} / ${shuffledQuestions.length}`;

}
