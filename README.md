# Dagobah Task 🛠️🚀

**Dagobah Task** is a simple and efficient open-source tool for managing tickets related to **GitLab**. Designed to automate the creation of issues, milestones, and other tasks, Dagobah Task offers an intuitive and modern backoffice built with **symfony.js** using **Api Platform** and **Sonata Admin**.

## 🎯 Objective

Our mission is to make ticket management in GitLab faster and more intuitive by eliminating repetitive and time-consuming tasks. Whether you’re a developer, project manager, or team member, Dagobah Task is designed to streamline your workflow.

##
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/web-obiwan/dagobah-task-backend/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/web-obiwan/dagobah-task-backend/?branch=develop)
[![Build Status](https://scrutinizer-ci.com/g/web-obiwan/dagobah-task-backend/badges/build.png?b=develop)]()

## 📖 Installation

### Prerequisites
- PHP >= 8.3
- Composer
- Symfony CLI
- A database (MySQL, PostgreSQL, SQLite, etc.)

### Steps

1. Clone the repository:
   ```bash
   git clone https://github.com/web-obiwan/dagobah-task-backend.git
   cd dagobah-task-backend
   ```

2. Install backend dependencies:
   ```bash
   composer install
   ```

3. Set up your environment:
    - Copy the `.env` file:
      ```bash
      cp .env .env.local
      ```
    - Edit the `.env.local` file to add your database connection settings and other environment variables.

4. Set up the database:
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   php bin/console audit:schema:update --force
   ```

5. Set up jwt private/public key:
   ```bash
   php bin/console lexik:jwt:generate-keypair
   ```

6. Start the development server:
   ```bash
   symfony serve
   ```

   Access the application at [http://localhost:8000](http://localhost:8000).

## Tests

To run unit tests:
```bash
php bin/phpunit
```

## 🤝 Contribution

We encourage the community to actively contribute to improving **Dagobah Task**! Here’s how you can help:

1. **Report a bug**: If you find a bug, open an issue in the repository.
2. **Suggest a feature**: If you have a great idea, let us know!
3. **Submit a pull request**: You can directly submit your contributions.
4. **Help with documentation**: Clear documentation is crucial for a successful project.

### Contribution Guidelines

- Fork the project.
- Create a dedicated branch (`feature/feature-name` or `fix/bug-description`).
- Ensure your changes pass tests.
- Submit your pull request with a clear description.

## 📜 License

This project is licensed under the [MIT](LICENSE) license. You are free to use, modify, and distribute it.

