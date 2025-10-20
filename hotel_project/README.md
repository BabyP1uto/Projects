Hotel Project API

Простое REST API для управления гостиничными номерами, гостями и бронированиями с использованием Flask и SQLAlchemy.

📂 Структура проекта
hotel_project/
├─ app.py                  # Основное приложение Flask
├─ requirements.txt        # Зависимости
├─ venv/                   # Виртуальное окружение
└─ README.md

⚡ Установка

Клонируем проект:

git clone <ссылка-на-репозиторий>
cd hotel_project


Создаём и активируем виртуальное окружение:

python -m venv venv
.\venv\Scripts\Activate.ps1   # Windows PowerShell


Устанавливаем зависимости:

pip install -r requirements.txt

🚀 Запуск приложения
python app.py


По умолчанию сервер будет работать на:

http://127.0.0.1:5000

🛠 Инициализация демо-данных
curl -X POST http://127.0.0.1:5000/init-demo


Ответ:

{
  "message": "Демо-данные созданы"
}

🔹 Примеры API
1. Получить список комнат
curl http://127.0.0.1:5000/api/rooms

2. Получить список бронирований
curl http://127.0.0.1:5000/api/reservations

3. Создать бронирование
curl -X POST http://127.0.0.1:5000/api/book -H "Content-Type: application/json" -d "{\"reservation_number\":\"RSV-0001\",\"guest\":{\"first_name\":\"Иван\",\"last_name\":\"Иванов\",\"passport_number\":\"1234567890\",\"phone_number\":\"+79876543210\",\"email\":\"ivan@example.com\"},\"room_id\":1,\"planned_checkin\":\"2025-10-20\",\"planned_checkout\":\"2025-10-22\",\"total_price\":6000.0,\"payment_method\":\"card\"}"


Ответ:

{
  "message": "Бронирование успешно создано",
  "reservation_id": 1
}

4. Получить гостя по паспорту
curl http://127.0.0.1:5000/api/guests/1234567890

📝 Замечания

Дата заезда и выезда не может быть в прошлом.

Если номер занят, бронирование не создаётся.

В разработке используется встроенный сервер Flask, не для продакшена.