<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# SpaceAI

## Overview

This Laravel project is designed as a comprehensive application leveraging cutting-edge technologies to deliver high performance and efficiency. The project incorporates:

- Gemini for advanced AI capabilities and natural language understanding.
- GroqCloud for scalable and accelerated data processing.
- Hugging Face API for machine learning models, including zero-shot classification and other NLP tasks.

## Features

- Seamless integration with Gemini for AI-driven functionalities.
- Utilization of GroqCloud for optimized data handling.
- Robust natural language processing using Hugging Face's pre-trained models.
- A clean and responsive UI for user interactions.

## Requirements

Ensure your environment meets the following requirements:

- PHP >= 8.1
- Laravel >= 10
- Composer
- API keys for Gemini, GroqCloud, and Hugging Face

## Installation

Follow these steps to set up the project locally:

### Step 1: Clone the Repository
```bash
git clone https://github.com/Nazrulalif/spaceAI.git
cd laravel-project
```
### Step 2: Update Composer
```bash
composer update
```
### Step 3: Set Up Environment Variables
Copy the `.env.example` file to create a new `.env` file:
```bash
cp .env.example .env
```
Edit the `.env` file to configure your  API keys:
```bash
GEMINI_API_KEY=your_gemini_api_key
GROQCLOUD_API_KEY=your_groqcloud_api_key
HUGGING_FACE_API_TOKEN=your_hugging_face_api_token
```
### Step 4: Start the Application
Serve the application locally:
```bash
php artisan serve
```
Access the application in your browser at `http://127.0.0.1:8000`.

## Contact
If you have any questions, reach out at: 
- Email: [nazrulism17@gmail.com] 
- GitHub: [https://github.com/Nazrulalif]
- Website: [https://nazrulalif.vercel.app/]

## Contributing
Contributions are welcome! Fork the repository, create a new branch, and submit a pull request for review.

## License
This project is licensed under the [MIT License](LICENSE)

Your Laravel application is now ready to use! 🚀