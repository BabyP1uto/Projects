/**
 * Пример принципов SOLID в TypeScript
 */

// ========== S: Single Responsibility Principle ==========
class User {
    constructor(public name: string, public email: string) {}
}

class UserRepository {
    saveToDatabase(user: User): void {
        console.log(`Saving user ${user.name} to database...`);
    }
}

class EmailService {
    sendEmail(user: User, message: string): void {
        console.log(`Sending email to ${user.email}: ${message}`);
    }
}

// ========== O: Open-Closed Principle ==========
abstract class Shape {
    abstract area(): number;  
}

class Circle extends Shape {
    constructor(public radius: number) {
        super();
    }

    area(): number {
        return Math.PI * this.radius ** 2;
    }
}

class Square extends Shape {
    constructor(public side: number) {
        super();
    }

    area(): number {
        return this.side ** 2;
    }
}

class AreaCalculator {
    static calculate(shapes: Shape[]): number {
        return shapes.reduce((sum, shape) => sum + shape.area(), 0);  
    }
}

// ========== L: Liskov Substitution Principle ==========
abstract class Bird {}

abstract class FlyingBird extends Bird {
    abstract fly(): void;
}

class Duck extends FlyingBird {
    fly(): void {
        console.log("Flying...");
    }
}

class Ostrich extends Bird {
    run(): void {
        console.log("Running fast!");
    }
}

// ========== I: Interface Segregation Principle ==========
interface Printer {
    print(): void;
}

interface Scanner {
    scan(): void;
}

class SimplePrinter implements Printer {
    print(): void {
        console.log("Printing...");
    }
}

class Photocopier implements Printer, Scanner {
    print(): void {
        console.log("Printing...");
    }

    scan(): void {
        console.log("Scanning...");
    }
}

// ========== D: Dependency Inversion Principle ==========
interface Database {
    save(data: string): void;
}

class MySQLDatabase implements Database {
    save(data: string): void {
        console.log(`Saving "${data}" to MySQL database`);
    }
}

class MongoDB implements Database {
    save(data: string): void {
        console.log(`Saving "${data}" to MongoDB`);
    }
}

class ReportService {
    constructor(private database: Database) {}

    generateReport(content: string): void {
        this.database.save(content);
    }
}

// ========== Пример использования ==========
console.log("=== Single Responsibility ===");
const user = new User("John", "john@example.com");
const userRepo = new UserRepository();
const emailService = new EmailService();
userRepo.saveToDatabase(user);
emailService.sendEmail(user, "Welcome!");

console.log("\n=== Open-Closed ===");
const shapes: Shape[] = [new Circle(5), new Square(4)];
console.log("Total area:", AreaCalculator.calculate(shapes));

console.log("\n=== Liskov Substitution ===");
const flyingBirds: FlyingBird[] = [new Duck()];
flyingBirds.forEach(bird => bird.fly());

const ostrich = new Ostrich();
ostrich.run();

console.log("\n=== Interface Segregation ===");
const devices: (Printer | Scanner)[] = [
    new SimplePrinter(),
    new Photocopier()
];
devices.forEach(device => {
    if ("print" in device) device.print();
    if ("scan" in device) device.scan();
});

console.log("\n=== Dependency Inversion ===");
const reportService1 = new ReportService(new MySQLDatabase());
reportService1.generateReport("Monthly Report");

const reportService2 = new ReportService(new MongoDB());
reportService2.generateReport("Annual Report");