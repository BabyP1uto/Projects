/**
 * Демонстрация 4 принципов ООП в TypeScript:
 * 1. Инкапсуляция
 * 2. Наследование
 * 3. Полиморфизм
 * 4. Абстракция
 */

// ==================== 1. ИНКАПСУЛЯЦИЯ ====================
class BankAccount {
    private _balance: number = 0;  // Приватное поле (инкапсуляция)

    // Геттер для контролируемого доступа
    get balance(): number {
        return this._balance;
    }

    public deposit(amount: number): void {
        if (amount > 0) {
            this._balance += amount;
        }
    }

    public withdraw(amount: number): boolean {
        if (amount <= this._balance) {
            this._balance -= amount;
            return true;
        }
        return false;
    }
}

// ==================== 2. НАСЛЕДОВАНИЕ ====================
class Animal {
    constructor(public name: string) {}

    public makeSound(): void {
        console.log("Some generic sound");
    }
}

class Dog extends Animal {
    constructor(name: string) {
        super(name);  // Вызов конструктора родителя
    }

    // Переопределение метода
    public makeSound(): void {
        console.log("Woof! Woof!");
    }

    // Новый метод, специфичный для Dog
    public fetch(): void {
        console.log(`${this.name} fetches the ball!`);
    }
}

// ==================== 3. ПОЛИМОРФИЗМ ====================
class Cat extends Animal {
    constructor(name: string) {
        super(name);
    }

    // Другая реализация метода
    public makeSound(): void {
        console.log("Meow!");
    }
}

// Функция, демонстрирующая полиморфизм
function playWithAnimal(animal: Animal): void {
    animal.makeSound();  // Будет вызвана соответствующая реализация
}

// ==================== 4. АБСТРАКЦИЯ ====================
abstract class Shape {
    abstract getArea(): number;  // Абстрактный метод

    // Общий метод для всех фигур
    public displayArea(): void {
        console.log(`Area: ${this.getArea()}`);
    }
}

class Circle extends Shape {
    constructor(private radius: number) {
        super();
    }

    // Реализация абстрактного метода
    public getArea(): number {
        return Math.PI * this.radius ** 2;
    }
}

class Square extends Shape {
    constructor(private side: number) {
        super();
    }

    public getArea(): number {
        return this.side ** 2;
    }
}

// ==================== ТЕСТИРОВАНИЕ ====================
console.log("=== Инкапсуляция ===");
const account = new BankAccount();
account.deposit(100);
console.log(account.balance);  // 100
account.withdraw(50);
console.log(account.balance);  // 50

console.log("\n=== Наследование ===");
const dog = new Dog("Rex");
dog.makeSound();  // "Woof! Woof!"
dog.fetch();      // "Rex fetches the ball!"

console.log("\n=== Полиморфизм ===");
const cat = new Cat("Whiskers");
playWithAnimal(dog);  // "Woof! Woof!"
playWithAnimal(cat);  // "Meow!"

console.log("\n=== Абстракция ===");
const circle = new Circle(5);
circle.displayArea();  // "Area: 78.53981633974483"

const square = new Square(4);
square.displayArea();  // "Area: 16"