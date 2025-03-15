Laravel Project

 🚀 Installation Guide  

Follow these steps to set up the project on your local machine.

 1️⃣ **Clone the Repository**  
```bash
git clone https://github.com/rahai-anis/product-management.git
cd product-management

2️⃣ Install Dependencies
composer install

3️⃣ Set Up Environment File
cp .env.example .env
Then, update the database configuration inside .env

4️⃣ Generate Application Key
php artisan key:generate

5️⃣ Set Up Database
php artisan migrate --seed

6️⃣ create a storage link
php artisan storage:link

7️⃣ run the app
php artisan serve

