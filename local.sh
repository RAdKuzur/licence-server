echo "Подготовка проекта..."
echo "Настраиваем окружение проекта..."
cp .env.example .env

echo "Устанавливаем бэкенд окружение..."
cd server && cp .env.example .env

echo "Устанавливаем фронтенд зависимости"
cd .. && docker compose up --build -d

#docker compose exec -it backend chown -R www-data:www-data /var/www/html/bootstrap/cache
#docker compose exec -it backend chmod -R 775 /var/www/html/bootstrap/cache