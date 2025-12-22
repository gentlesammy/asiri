# TODO: Implement Username Rep Generation Function

- [x] Add private method `generateRep($username)` to RegisteredUserController.php that extracts first 3 characters of username and concatenates a random 6-digit number.
- [x] Update the `store` method in RegisteredUserController.php to set 'rep' => $this->generateRep($request->username) during user creation.
