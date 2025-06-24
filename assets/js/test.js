//Класс, который представляет сам тест

class Test {
  constructor(questions)
   {
      //Массив с вопросами
      this.questions = questions;

      this.questionForm = document.querySelector('.test__answers-form');
 
       //Номер текущего вопроса
       this.current = 0;
   }

   testInit() {
    this.questions[this.current].renderQuestion();
    this.questionForm.addEventListener('submit', ()=>{this.handleFormSubmit()});
    console.log(this);
   }

    handleFormSubmit(number) {
      event.preventDefault();
      this.questions[this.current].chosenAnswer.countScore();

      ++this.current;
      this.questions[this.current].renderQuestion();
    }

}

//Класс, представляющий вопрос
class Question
{
  constructor(text, answers)
  {
    this.text = text;
    this.answers = answers;
    this.questionElement = document.querySelector('.test__question');
    this.answersList = document.querySelector('.test__answers');
    // this.questionForm = document.querySelector('.test__answers-form');
    this.chosenAnswer = this.chosenAnswer;
  }
 
  renderQuestion() {
    this.answersList.innerHTML = "";
    this.questionElement.innerText = this.text;

    this.answers.forEach(answer => {
      this.answerElementDiv = document.createElement('div');
      this.answerElementInput = document.createElement('input');
      this.answerElementLabel = document.createElement('label');
      this.answerElementInput.setAttribute('type', 'radio');
      this.answerElementInput.setAttribute('name', 'answer');
      this.answerElementLabel.innerText = answer.text;
      this.answerElementDiv.appendChild(this.answerElementInput);
      this.answerElementDiv.appendChild(this.answerElementLabel);
      this.answersList.appendChild(this.answerElementDiv);
      this.answerElementInput.addEventListener('click', ()=>{this.handleInputClick(answer)});
    })
  }
  
  handleInputClick(answer) {
    this.chosenAnswer = answer;
    console.log(this.chosenAnswer);
  }

}
 
//Класс, представляющий ответ
class Answer
{
   constructor(text, type)
   {
       this.text = text;
       this.type = type;
   }

   countScore () {
     console.log('answer type is ', this.type.name)
     console.log('answer score is ', this.type.score)
     
     ++this.type.score;
     
     console.log('answer score is ', this.type.score)

   }
}

const plantTypes = [
  {name:'0 Замиокулькас',
    score: 0,
    result: 'lalalal',
  },
  {name:'1 Спатифиллум',
    score: 0,
    result: 'lalalal',
  },
  {name:'2 Хризалидокарпус',
    score: 0,
    result: 'lalalal',
  },
  {name:'3 Фикус Бенджамина',
    score: 0,
    result: 'lalalal',
  },
  {name:'4 Фикус эластика',
    score: 0,
    result: 'lalalal',
  },
  {name:'5 Антуриум',
    score: 0,
    result: 'lalalal',
  },
  {name:'6 Аглаонема',
    score: 0,
    result: 'lalalal',
  },
  {name:'7 Драцена',
    score: 0,
    result: 'lalalal',
  },
  {name:'8 Сансевиерия',
    score: 0,
    result: 'lalalal',
  },
  {name:'9 Эпипремнум',
    score: 0,
    result: 'lalalal',
  }
]

//Массив с вопросами
const questions = 
[
   new Question("Какой вид отдыха тебе ближе всего?",
   [
       new Answer("Сидеть дома, обложившись пледами, сериалами и заказной едой – идеальный уют.", plantTypes[9]),
       new Answer("Уехать на пляж, надеть солнечные очки и забыть о делах.", plantTypes[2]),
       new Answer("Отправиться в спонтанное приключение или начать ремонт. Ну, типа, для души.", plantTypes[6]),
       new Answer("Провести день в одиночестве, читая философские трактаты под кружку чая.", plantTypes[3])
   ]),

   new Question("Как ты ведёшь себя на вечеринке?",
   [
       new Answer("Я в центре внимания, делаю эффектный вход, все обсуждают мой лук.", plantTypes[5]),
       new Answer("Я сижу в уголке, наблюдаю и делаю умные выводы.", plantTypes[8]),
       new Answer("Завожу душевные разговоры и внимательно слушаю других.", plantTypes[1]),
       new Answer("Я там, где весело, но всегда готов(а) сбежать по-тихому, если станет скучно.", plantTypes[7])
   ]),

];


const test = new Test(questions);

test.testInit();