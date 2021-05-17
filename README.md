# Turan Schedule


## АНДАТПА 
 
Бұл дипломдық жоба сабақ кестесін жүргізу және редакциялау үшін әлеуметтік желі элементтері мен веб-клиент бар қызметті әзірледі. Сервис кесте деректерін, сондай-ақ студенттер деректерін сақтау үшін Дерекқорды пайдаланады. Сервисте оның бүтін немесе жекелеген бөліктерін басқа сервистер мен қосымшаларға енгізу мүмкіндігі үшін API қарастырылған. 
 
 
## АННОТАЦИЯ 
 
В данном дипломном проекте разработан сервис с элементами социальной сети и веб-клиент для ведения и редактирования расписания занятий. Сервис использует базу данных для хранения данных расписания, а также данных студентов. В сервисе предусмотрено API для возможности внедрения целых или отдельных его частей в другие сервисы и приложения. 
 
 
## ANNOTATION 
 
In this diploma project, a service with elements of a social network and a web client for maintaining and editing class schedules have been developed. The service uses a database to store schedule data, as well as student data. The service provides an API for the ability to embed whole or individual parts of it in other services and applications. 

## Настройка

Для корректной работы необходимо создать файл ```db-info.php``` с настройками базы данных в корне проекта.

**Пример файла :**

```php
<?php
  define( 'MYSQL_SERVER', 'адрес сервера' );
  define( 'MYSQL_USER', 'имя пользователя' );
  define( 'MYSQL_PASSWORD', 'пароль' );
  define( 'MYSQL_DB', 'имя базы данных' );
?>
```
