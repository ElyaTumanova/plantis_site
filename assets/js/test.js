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
   }
 
   Click(index)
   {
       return this.answers[index].value;
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
     console.log(this.type)
   }
}

const plantTypes = [
  {name:'0 Замиокулькас',
    score: 0
  },
  '1 Спатифиллум',
  '2 Хризалидокарпус',
  '3 Фикус Бенджамина',
  '4 Фикус эластика',
  '5 Антуриум',
  '6 Аглаонема',
  '7 Драцена',
  '8 Сансевиерия',
  '9 Эпипремнум'
]

//Массив с вопросами
const questions =
[
   new Question("Какой вид отдыха тебе ближе всего?",
   [
       new Answer("Сидеть дома, обложившись пледами, сериалами и заказной едой – идеальный уют.", plantTypes[9].name),
       new Answer("Уехать на пляж, надеть солнечные очки и забыть о делах.", plantTypes[2]),
       new Answer("Отправиться в спонтанное приключение или начать ремонт. Ну, типа, для души.", plantTypes[6]),
       new Answer(" Провести день в одиночестве, читая философские трактаты под кружку чая.", plantTypes[3])
   ])
];

questions.Click();

console.log(plantTypes[0].name);
console.log(plantTypes[0].score);

 