echo "Подготовка проекта..."
echo "Настраиваем окружение проекта..."
cp .env.example .env

echo "Устанавливаем бэкенд окружение..."
cd server && cp .env.example .env

echo "Устанавливаем фронтенд зависимости"
cd .. && docker compose up --build -d