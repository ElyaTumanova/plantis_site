//Класс, который представляет сам тест

class Quiz {
  constructor(questions, results, plantTypes)
   {
       //Массив с вопросами
       this.questions = questions;
 
       //Массив с возможными результатами
       this.results = results;
 
       //Количество набранных очков
       this.plantTypes = plantTypes;
 
       //Номер результата из массива
       this.result = 0;
 
       //Номер текущего вопроса
       this.current = 0;
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
      this.answersElement = document.querySelector('.test__answers');
   }
 
   RenderQuestion() {
      // console.log(this.questionElement);
      // console.log(this.answersElement);
      // console.log(this.text);
      this.questionElement.innerText = this.text;

      this.answers.forEach(answer => {
        this.answerElement = document.createElement('li');
        this.answerElement.innerText = answer.text;
        // this.answerElement.addEventListener('click', answer.Click);
        this.answerElement.addEventListener('click', ()=>{console.log(this)});
        this.answersElement.appendChild(this.answerElement);
      })
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

   Click () {
     console.log('answer type is ', this.type.name)
     console.log('answer score is ', this.type.score)
     
     ++this.type.score;
     
     console.log('answer score is ', this.type.score)

   }
}

const plantTypes = [
  {name:'0 Замиокулькас',
    score: 0
  },
  {name:'0 Замиокулькас',
    score: 0
  },
  {name:'0 Замиокулькас',
    score: 0
  },
  {name:'0 Замиокулькас',
    score: 0
  },
  {name:'1 Спатифиллум',
    score: 0
  },
  {name:'2 Хризалидокарпус',
    score: 0
  },
  {name:'3 Фикус Бенджамина',
    score: 0
  },
  {name:'4 Фикус эластика',
    score: 0
  },
  {name:'5 Антуриум',
    score: 0
  },
  {name:'6 Аглаонема',
    score: 0
  },
  {name:'7 Драцена',
    score: 0
  },
  {name:'8 Сансевиерия',
    score: 0
  },
  {name:'9 Эпипремнум',
    score: 0
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
       new Answer(" Провести день в одиночестве, читая философские трактаты под кружку чая.", plantTypes[3])
   ])
];


questions.forEach(q => {
  console.log(q);
  console.log(q.text);
  console.log(q.answers[0]);
  q.answers[0].Click();
  q.RenderQuestion();


});

console.log(plantTypes[0].name);
console.log(plantTypes[0].score);

 