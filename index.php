<?php

class Player{
    public $name;
    public $coins;
    public function __construct($name, $coins)
    {
        $this->name = $name;
        $this->coins = $coins;
    }
    //+1 монета победителю, -1 монета проигравшему
    public function exchange(Player $player)
    {
        $this->coins++;
        $player->coins--;
    }
    //состояние игрока при нулевом балансе
    public function bankrupt()
    {
        return $this->coins == 0;
    }
    public function bank()
    {
        return $this->coins;
    }
    //подсчёт шансов для каждого игрока
    public function odds(Player $player)
    {
        return round($this->bank() / ($this->bank() + $player->bank()), 2) *100 . '%';

    }
}


class Game
{
    protected $player1;
    protected $player2;
    protected $flips = 1;


    public function __construct(Player $player1, Player $player2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
    }
    //подбросить монету
    public function flip()
    {
        return rand(0, 1) ? 'орёл' : 'решка';
    }
    public function start()
    {
        echo <<<EOT
        {$this->player1->name}: odds is {$this->player1->odds($this->player2)}
        {$this->player2->name}: odds is {$this->player2->odds($this->player1)}
EOT;

        $this->play();
    }
    public function play()
    {
        while (true) {

            //если орёл, п1 получает поинт, если решка - п2 получает поинт
            if ($this->flip() == 'орёл') {
                $this->player1->exchange($this->player2);
            } else {
                $this->player2->exchange($this->player1);
            }

            //если п1/п2 банкрот - игра заканчивается
            if ($this->player1->bankrupt() || $this->player2->bankrupt()) {
                return $this->end();
            }
            $this->flips++;
        }
    }
public function winner() : Player
    {
        //у кого больше итоговый банк - тот и выиграл
        return ($this->player1->bank()>$this->player2->bank() ? $this->player1 : $this->player2);
    }

public function end()
{
    echo <<<EOT
    Game over.
    {$this->player1->name} : {$this->player1->bank()}
    {$this->player2->name} : {$this->player2->bank()}
   Winner is - {$this->winner()->name}
   Flips count - {$this->flips}
EOT;
}
}

$game = new Game(
  new Player('KadiZ', 1000),
  new Player('Marakuiiya', 100)
);

$game->start();
