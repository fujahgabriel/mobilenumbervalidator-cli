
##  Cli Mobile Number Validator  (Uk and Channels Island)

A sample mobile number validator using Laravel Cli command and Google's libphonenumber package.

### Usage

Ability to validate numbers as a "List" or "CSV" file:

##### Validate as a List of numbers

Pass a list of mobile numbers to the validator.

Note: change sample numbers to real numbers for effective usage.

```bash
$ php mobilenumbervalidator-cli validate:number "0740000000" "07200000000" 
```

##### Validate numbers in a CSV file

Pass csv file with a mobile number per line.

07400000000,


07200000000 

```bash
$ php mobilenumbervalidator-cli validate:number /path/to/file --file
```


