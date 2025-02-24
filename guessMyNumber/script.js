'use strict';
let randomNumber = Math.trunc(Math.random() * 21);
let highscore=0;
console.log(randomNumber);
const displayMessage = function(message){
    document.querySelector('.message').textContent=message;
};
document.querySelector('.check').addEventListener('click', function () {
    let currentScore=Number(document.querySelector('.score').textContent);
  let guess = Number(document.querySelector('.guess').value);
  console.log(highscore);
  if (guess === 0) {
    displayMessage('⛔ No Number!');
  } else if (guess === randomNumber) {
    document.querySelector('.number').textContent = randomNumber;
    displayMessage('🎉 Correct Number!');
    document.querySelector('body').style.backgroundColor = 'green';
    if (highscore < currentScore) {
      document.querySelector('.highscore').textContent = Number(
        document.querySelector('.score').textContent
      );
      highscore = currentScore;
    }
  }
  if (guess !== randomNumber) {
    if (guess > randomNumber) {
      displayMessage('📈 Too High!');
      document.querySelector('.score').textContent =
        currentScore - 1;
    } else if (guess < randomNumber) {
      displayMessage('📉 Too Low!');
      document.querySelector('.score').textContent =
        currentScore - 1;
    }
  }
  if (currentScore === 0) {
    displayMessage('💥 You Lost!');
    document.querySelector('.score').textContent = 0;
    document.querySelector('body').style.backgroundColor = 'red';
    document.querySelector('.check').disabled = true;
  }
});

// Coding Challenge #1

/* 
Implement a game rest functionality, so that the player can make a new guess! Here is how:

1. Select the element with the 'again' class and attach a click event handler
2. In the handler function, restore initial values of the score and secretNumber variables
3. Restore the initial conditions of the message, number, score and guess input field
4. Also restore the original background color (#222) and number width (15rem)

GOOD LUCK 😀
*/

document.querySelector('.again').addEventListener('click', function () {
  randomNumber = Math.trunc(Math.random() * 21);
  console.log(randomNumber);
  document.querySelector('.score').textContent = 20;
  displayMessage('Start guessing...');
  document.querySelector('.number').textContent = '?';
  document.querySelector('.guess').value = '';
  document.querySelector('body').style.backgroundColor = '#222';
  document.querySelector('.check').disabled = false;
});
