# Twig templates application

# Project Overview:
```
Application used GraphQL queries and Twig templates.
```
# Technologies Used
Backend: PHP, GraphQL.
Frontentd: Javascript, jQuery 3+, Bootstrap 4.3.
Authentication: no.
Data format: JSON.
Deployment: GitHub.

# Application features
- User select different values from the select menu.
- 'GET' button return a list with response data.
![screen](https://github.com/bart-git21/JS-jQuery-Bootstrap-PHP-Twig--rickandmortyapi/blob/main/result.jpg)

### Base api URL
localhost/index.php

# Endpoint

## POST /api/
Get rickandmortyapi characters, locations and episodes.
### Client request example
* **Headers**: 'Content-Type': 'application/json'
* **Body**:
```
{
    "nameSelect": "Rick",
}
```
### api response example
* **Status code**: 200
* **Headers**: 'Content-Type': 'application/json'
* **Body**:
```
{
    "data": {
        "characters": {
            "info": {count: 107}
            "results": [
                {
                    image: "https://rickandmortyapi.com/api/character/avatar/1.jpeg",
                    name:  "Rick Sanchez",
                    species: "Human",
                    status: "Alive",
                },
                {...}
            ]
        }
    }
}
```
### Error Handling
- 401 Unauthorized: authentication failed or missing
