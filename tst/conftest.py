import os
import time
import pytest
from selenium import webdriver
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from pytest_html import extras

BASE_URL = "https://demo-opencart.ru"

def pytest_configure(config):
    # создаём папки для отчетов и скриншотов
    os.makedirs("reports", exist_ok=True)
    os.makedirs(os.path.join("reports", "screenshots"), exist_ok=True)

@pytest.fixture(scope="session")
def base_url():
    return BASE_URL

@pytest.fixture()
def browser():
    options = webdriver.ChromeOptions()
    options.add_argument("--window-size=1920,1080")
    service = Service(ChromeDriverManager().install())
    driver = webdriver.Chrome(service=service, options=options)
    driver.implicitly_wait(5)
    yield driver
    driver.quit()

@pytest.fixture()
def new_user(browser, base_url):
    """Register a new user via UI and return credentials"""
    email = f"user_{int(time.time())}@example.com"
    password = "Qq12345678"
    browser.get(base_url + "/index.php?route=account/register")
    wait = WebDriverWait(browser, 10)
    wait.until(EC.visibility_of_element_located((By.ID, "input-firstname"))).send_keys("Ivan")
    browser.find_element(By.ID, "input-lastname").send_keys("Petrov")
    browser.find_element(By.ID, "input-email").send_keys(email)
    browser.find_element(By.ID, "input-telephone").send_keys("1234567890")
    browser.find_element(By.ID, "input-password").send_keys(password)
    browser.find_element(By.ID, "input-confirm").send_keys(password)
    # согласие (если есть)
    try:
        agree = browser.find_element(By.NAME, "agree")
        if not agree.is_selected():
            agree.click()
    except Exception:
        pass
    # отправить
    browser.find_element(By.CSS_SELECTOR, "input.btn-primary").click()
    # ждём редирект на success страницу
    WebDriverWait(browser, 10).until(lambda d: "route=account/success" in d.current_url)
    return {"email": email, "password": password}

# Сохраняем скриншот при падении и добавляем в pytest-html
@pytest.hookimpl(hookwrapper=True)
def pytest_runtest_makereport(item, call):
    outcome = yield
    rep = outcome.get_result()
    if rep.when == "call" and rep.failed:
        driver = item.funcargs.get("browser")
        if driver:
            screenshots_dir = os.path.join("reports", "screenshots")
            os.makedirs(screenshots_dir, exist_ok=True)
            png_path = os.path.join(screenshots_dir, f"{item.name}_{int(time.time())}.png")
            driver.save_screenshot(png_path)
            # прикрепим к html-отчету (pytest-html)
            extra = getattr(rep, "extra", [])
            extra.append(extras.image(png_path))
            rep.extra = extra
