#Routes
```
⇒  php artisan route:list
+----------+--------------------------------+-------------------------------+------------------------------------------+-------------------+--------------------------+
| Verb     | Path                           | NamedRoute                    | Controller                               | Action            | Middleware               |
+----------+--------------------------------+-------------------------------+------------------------------------------+-------------------+--------------------------+
| POST     | api/auth/login                 | api.auth.login                | App\Http\Controllers\Auth\AuthController | postLogin         | api.controllers          |
| POST     | api/project/create             | api.project.create            | App\Http\Controllers\ProjectController   | create            | api.controllers|api.auth |
| POST     | api/project/edit               | api.project.edit              | App\Http\Controllers\ProjectController   | edit              | api.controllers|api.auth |
| POST     | api/project/delete             | api.project.delete            | App\Http\Controllers\ProjectController   | delete            | api.controllers|api.auth |
| POST     | api/project/add-member         | api.project.addMember         | App\Http\Controllers\ProjectController   | addMember         | api.controllers|api.auth |
| POST     | api/project/request-membership | api.project.requestMembership | App\Http\Controllers\ProjectController   | requestMembership | api.controllers|api.auth |
| POST     | api/user/search                | api.user.search               | App\Http\Controllers\UserController      | search            | api.controllers|api.auth |
| GET|HEAD | api/auth/user                  | api.auth.user                 | App\Http\Controllers\Auth\AuthController | getUser           | api.controllers|api.auth |
| PATCH    | api/auth/refresh               | api.auth.refresh              | App\Http\Controllers\Auth\AuthController | patchRefresh      | api.controllers|api.auth |
| DELETE   | api/auth/invalidate            | api.auth.invalidate           | App\Http\Controllers\Auth\AuthController | deleteInvalidate  | api.controllers|api.auth |
+----------+--------------------------------+-------------------------------+------------------------------------------+-------------------+--------------------------+
```

#Request Format
For every guarded route, make sure to pass the following header:
Authorization: Bearer a_long_token_appears_here