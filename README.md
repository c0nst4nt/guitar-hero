### Implementation of https://www.cs.princeton.edu/courses/archive/spring18/cos126/assignments/guitar-hero/

My course work for projector algorithms course. This algorithm emulates or synthesizes the sound of guitar string. 

This is a small cli project. Here are the set of "bin" files, which are generating .wav files and charts, visualising 
the values of a signal. All generated content is placed inside /generated/audio and /generated/images folders. This project 
is using some external packages, to simplify sound & charts generating and also 
creating simple socket server (to avoid using web servers, pure cli).

##### All you need to make this working is:
* PHP 7.4
* Composer

1) Run ```composer install```
2) Run any /bin/ file

### Examples:

#### Generate single file with 1 second of a note A (1th octave)
```php ./bin/createSin.php```  
Try to run ./generated/audio/sin.wav

#### Generate single file with 1 second of a guitar synthesized note A (1th octave)
```php ./bin/testCarplusStrongWN.php```  
Try to run ./generated/audio/a_4.wav

#### Generate guitar synthesized God Father melody
```php ./bin/testCarplusStrongWN.php```  
Try to run ./generated/audio/god_father.wav

#### Create virtual piano keyboard with sound of synthesized guitar notes
```php ./bin/createNotes2-5oct.php```  
After that, there should be lots of files inside ./generated/audio/notes/.
Then run sockets server:  
```php ./bin/startSocketServer.php```  
Then open ```./public/index.html``` in browser and try to click any button!