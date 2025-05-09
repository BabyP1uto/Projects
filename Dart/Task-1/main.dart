
// 1. Класс "Человек"
class Person {
  String name;
  int age;
  String gender;

  Person(this.name, this.age, this.gender);

  void displayInfo() {
    print('Name: $name, Age: $age, Gender: $gender');
  }

  void increaseAge() {
    age++;
  }

  void changeName(String newName) {
    name = newName;
  }
}

// 2. Наследование: Класс "Работник" и "Менеджер"
class Worker extends Person {
  double salary;

  Worker(String name, int age, String gender, this.salary) : super(name, age, gender);

  void displayWorkerInfo() {
    displayInfo();
    print('Salary: $salary');
  }
}

class Manager extends Worker {
  List<Worker> subordinates;

  Manager(String name, int age, String gender, double salary, this.subordinates)
      : super(name, age, gender, salary);

  void displayManagerInfo() {
    displayWorkerInfo();
    print('Subordinates: ${subordinates.length}');
  }
}

// 3. Полиморфизм: Животные
abstract class Animal {
  void makeSound();
}

class Dog implements Animal {
  @override
  void makeSound() {
    print('Woof!');
  }
}

class Cat implements Animal {
  @override
  void makeSound() {
    print('Meow!');
  }
}

class Cow implements Animal {
  @override
  void makeSound() {
    print('Moo!');
  }
}

void demonstratePolymorphism() {
  List<Animal> animals = [Dog(), Cat(), Cow()];
  for (var animal in animals) {
    animal.makeSound();
  }
}

// 4. Абстрактный класс "Транспорт"
abstract class Transport {
  void move();
}

class Car extends Transport {
  @override
  void move() {
    print('Car is moving');
  }
}

class Bike extends Transport {
  @override
  void move() {
    print('Bike is moving');
  }
}

// 5. Класс "Книга" и "Библиотека"
class Book {
  String title;
  String author;
  int year;

  Book(this.title, this.author, this.year);
}

class Library {
  List<Book> books = [];

  void addBook(Book book) {
    books.add(book);
  }

  List<Book> findBooksByAuthor(String author) {
    return books.where((book) => book.author == author).toList();
  }

  List<Book> findBooksByYear(int year) {
    return books.where((book) => book.year == year).toList();
  }
}

// 6. Инкапсуляция: Банк
class BankAccount {
  String _accountNumber;
  double _balance;

  BankAccount(this._accountNumber, this._balance);

  String get accountNumber => _accountNumber;
  double get balance => _balance;

  void deposit(double amount) {
    _balance += amount;
  }

  void withdraw(double amount) {
    if (_balance >= amount) {
      _balance -= amount;
    } else {
      print('Insufficient funds');
    }
  }
}

// 7. Счетчик объектов
class Counter {
  static int _count = 0;

  Counter() {
    _count++;
  }

  static int get count => _count;
}

// 8. Площадь фигур
abstract class Shape {
  double getArea();
}

class Circle extends Shape {
  double radius;

  Circle(this.radius);

  @override
  double getArea() {
    return 3.14 * radius * radius;
  }
}

class Rectangle extends Shape {
  double width;
  double height;

  Rectangle(this.width, this.height);

  @override
  double getArea() {
    return width * height;
  }
}

// 9. Наследование: Животные и их движения
class AnimalWithMove {
  void move() {
    print('Animal is moving');
  }
}

class Fish extends AnimalWithMove {
  @override
  void move() {
    print('Fish is swimming');
  }
}

class Bird extends AnimalWithMove {
  @override
  void move() {
    print('Bird is flying');
  }
}

class DogWithMove extends AnimalWithMove {
  @override
  void move() {
    print('Dog is running');
  }
}

// 10. Работа с коллекциями: Университет
class Student {
  String name;
  String group;
  double grade;

  Student(this.name, this.group, this.grade);
}

class University {
  List<Student> students = [];

  void addStudent(Student student) {
    students.add(student);
  }

  void sortStudentsByName() {
    students.sort((a, b) => a.name.compareTo(b.name));
  }

  List<Student> filterStudentsByGrade(double minGrade) {
    return students.where((student) => student.grade >= minGrade).toList();
  }
}

// 11. Класс "Магазин"
class Product {
  String name;
  double price;
  int quantity;

  Product(this.name, this.price, this.quantity);
}

class Store {
  List<Product> products = [];

  void addProduct(Product product) {
    products.add(product);
  }

  void removeProduct(String name) {
    products.removeWhere((product) => product.name == name);
  }

  Product? findProductByName(String name) {
    return products.firstWhere((product) => product.name == name);
  }
}

// 12. Интерфейс "Платежная система"
abstract class PaymentSystem {
  void pay(double amount);
  void refund(double amount);
}

class CreditCard implements PaymentSystem {
  @override
  void pay(double amount) {
    print('Paid $amount with Credit Card');
  }

  @override
  void refund(double amount) {
    print('Refunded $amount to Credit Card');
  }
}

class PayPal implements PaymentSystem {
  @override
  void pay(double amount) {
    print('Paid $amount with PayPal');
  }

  @override
  void refund(double amount) {
    print('Refunded $amount to PayPal');
  }
}

// 13. Генерация уникальных идентификаторов
class UniqueID {
  static int _idCounter = 0;
  int id;

  UniqueID() : id = _idCounter++;
}

// 14. Класс "Точка" и "Прямоугольник"
class Point {
  double x;
  double y;

  Point(this.x, this.y);
}

class RectangleWithPoints {
  Point topLeft;
  Point bottomRight;

  RectangleWithPoints(this.topLeft, this.bottomRight);

  double getArea() {
    double width = bottomRight.x - topLeft.x;
    double height = topLeft.y - bottomRight.y;
    return width * height;
  }
}

// 15. Комплексные числа
class ComplexNumber {
  double real;
  double imaginary;

  ComplexNumber(this.real, this.imaginary);

  ComplexNumber add(ComplexNumber other) {
    return ComplexNumber(real + other.real, imaginary + other.imaginary);
  }

  ComplexNumber subtract(ComplexNumber other) {
    return ComplexNumber(real - other.real, imaginary - other.imaginary);
  }

  ComplexNumber multiply(ComplexNumber other) {
    return ComplexNumber(
      real * other.real - imaginary * other.imaginary,
      real * other.imaginary + imaginary * other.real,
    );
  }

  ComplexNumber divide(ComplexNumber other) {
    double denominator = other.real * other.real + other.imaginary * other.imaginary;
    return ComplexNumber(
      (real * other.real + imaginary * other.imaginary) / denominator,
      (imaginary * other.real - real * other.imaginary) / denominator,
    );
  }
}

// 16. Перегрузка операторов: Матрица
class Matrix {
  List<List<int>> data;

  Matrix(this.data);

  Matrix operator +(Matrix other) {
    List<List<int>> result = [];
    for (int i = 0; i < data.length; i++) {
      List<int> row = [];
      for (int j = 0; j < data[i].length; j++) {
        row.add(data[i][j] + other.data[i][j]);
      }
      result.add(row);
    }
    return Matrix(result);
  }

  Matrix operator *(Matrix other) {
    List<List<int>> result = [];
    for (int i = 0; i < data.length; i++) {
      List<int> row = [];
      for (int j = 0; j < other.data[0].length; j++) {
        int sum = 0;
        for (int k = 0; k < data[i].length; k++) {
          sum += data[i][k] * other.data[k][j];
        }
        row.add(sum);
      }
      result.add(row);
    }
    return Matrix(result);
  }
}

// 17. Создание игры с использованием ООП
class Player {
  String name;
  int health;
  Weapon weapon;

  Player(this.name, this.health, this.weapon);

  void attack(Enemy enemy) {
    enemy.takeDamage(weapon.damage);
  }
}

class Enemy {
  String name;
  int health;

  Enemy(this.name, this.health);

  void takeDamage(int damage) {
    health -= damage;
    if (health <= 0) {
      print('$name defeated!');
    }
  }
}

class Weapon {
  String name;
  int damage;

  Weapon(this.name, this.damage);
}

// 18. Автоматизированная система заказов
class Order {
  int id;
  List<Product> products;

  Order(this.id, this.products);

  double getTotalCost() {
    return products.fold(0, (sum, product) => sum + product.price);
  }
}

class Customer {
  String name;
  List<Order> orders = [];

  Customer(this.name);

  void addOrder(Order order) {
    orders.add(order);
  }

  void displayOrderHistory() {
    for (var order in orders) {
      print('Order ID: ${order.id}, Total Cost: ${order.getTotalCost()}');
    }
  }
}

// 19. Наследование: Электроника
class Device {
  String brand;

  Device(this.brand);

  void turnOn() {
    print('$brand is turned on');
  }

  void turnOff() {
    print('$brand is turned off');
  }
}

class Smartphone extends Device {
  Smartphone(String brand) : super(brand);

  void takePhoto() {
    print('$brand is taking a photo');
  }
}

class Laptop extends Device {
  Laptop(String brand) : super(brand);

  void openLid() {
    print('$brand laptop lid is opened');
  }
}

// 20. Игра "Крестики-нолики"
class TicTacToeGame {
  List<List<String>> board = List.generate(3, (_) => List.filled(3, ''));
  Player player1;
  Player player2;
  Player currentPlayer;

  TicTacToeGame(this.player1, this.player2) : currentPlayer = player1;

  void makeMove(int row, int col) {
    if (board[row][col] == '') {
      board[row][col] = currentPlayer.name;
      if (checkWin()) {
        print('${currentPlayer.name} wins!');
      } else {
        currentPlayer = currentPlayer == player1 ? player2 : player1;
      }
    } else {
      print('Cell is already occupied');
    }
  }

  bool checkWin() {
    for (int i = 0; i < 3; i++) {
      if (board[i][0] != '' && board[i][0] == board[i][1] && board[i][1] == board[i][2]) {
        return true;
      }
      if (board[0][i] != '' && board[0][i] == board[1][i] && board[1][i] == board[2][i]) {
        return true;
      }
    }
    if (board[0][0] != '' && board[0][0] == board[1][1] && board[1][1] == board[2][2]) {
      return true;
    }
    if (board[0][2] != '' && board[0][2] == board[1][1] && board[1][1] == board[2][0]) {
      return true;
    }
    return false;
  }
}

void main() {
  // Пример использования всех классов
  Person person = Person('John', 30, 'Male');
  person.displayInfo();

  Worker worker = Worker('Alice', 25, 'Female', 50000);
  worker.displayWorkerInfo();

  demonstratePolymorphism();

  Car car = Car();
  car.move();

  Library library = Library();
  library.addBook(Book('Dart Programming', 'John Doe', 2021));
  print(library.findBooksByAuthor('John Doe'));

  BankAccount account = BankAccount('123456', 1000);
  account.deposit(500);
  print(account.balance);

  Counter();
  Counter();
  print(Counter.count);

  Circle circle = Circle(5);
  print('Circle Area: ${circle.getArea()}');

  Fish fish = Fish();
  fish.move();

  University university = University();
  university.addStudent(Student('Bob', 'A', 4.5));
  university.sortStudentsByName();

  Store store = Store();
  store.addProduct(Product('Laptop', 1000, 10));
  print(store.findProductByName('Laptop')?.price);

  CreditCard creditCard = CreditCard();
  creditCard.pay(100);

  UniqueID uniqueID = UniqueID();
  print(uniqueID.id);

  RectangleWithPoints rectangle = RectangleWithPoints(Point(0, 0), Point(4, 4));
  print('Rectangle Area: ${rectangle.getArea()}');

  ComplexNumber complex1 = ComplexNumber(1, 2);
  ComplexNumber complex2 = ComplexNumber(3, 4);
  print(complex1.add(complex2).real);

  Matrix matrix1 = Matrix([
    [1, 2],
    [3, 4]
  ]);
  Matrix matrix2 = Matrix([
    [5, 6],
    [7, 8]
  ]);
  print((matrix1 + matrix2).data);

  Player player = Player('Hero', 100, Weapon('Sword', 20));
  Enemy enemy = Enemy('Goblin', 50);
  player.attack(enemy);

  Customer customer = Customer('Alice');
  customer.addOrder(Order(1, [Product('Book', 10, 1)]));
  customer.displayOrderHistory();

  Smartphone smartphone = Smartphone('iPhone');
  smartphone.takePhoto();

  TicTacToeGame game = TicTacToeGame(Player('X', 100, Weapon('None', 0)), Player('O', 100, Weapon('None', 0)));
  game.makeMove(0, 0);
}