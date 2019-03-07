# TetrisTable

HTML table based clone of Tetris.

## How to run

Simply run a server like this:

`php -S localhost`

and go to localhost on the browser.

Refresh when you want.

## How to play

Use `left-arrow` and `right-arrow` to move the piece; use `up-arrow` to rotate the piece; use `down-arrow` to make the piece fall in place more quickly.

## Accepted options

Via get parameters you can specify:

* `level`: (int) initial level; influence score;
* `speed`: (int) game's speed;
* `threedimode`: (bool: 1,0) enable "3D" mode (which is not 3D).