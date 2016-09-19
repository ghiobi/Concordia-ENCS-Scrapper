# vanlew
A script to scrape Concordia's ENCS and CS sections on [https://aits.encs.concordia.ca/oldsite/resources/schedules/courses/](https://aits.encs.concordia.ca/oldsite/resources/schedules/courses/).

### How to use
1. Install dependencies `composer install`
2. Execute script `php sections.php`

### Format
```
[
   {
        "number": "COMP248",
        "name": "Object-Oriented Programming I",
        "sections": [
            {
                "type": "Tut",
                "section": "EEEA",
                "day": "-T-----",
                "start": "11:45",
                "end": "13:25",
                "room": "H929 *",
                "instructor": ""
            },
            {
                "type": "Lab",
                "section": "EI-X",
                "day": "M------",
                "start": "19:45",
                "end": "20:45",
                "room": "H917 *",
                "instructor": ""
            },
...
```

