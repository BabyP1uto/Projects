import time
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

def test_registration_new_user(browser, base_url):
    """
    Цель: проверить регистрацию нового пользователя.
    Ожидаемый результат: после регистрации происходит переход на страницу success.
    """
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
    try:
        agree = browser.find_element(By.NAME, "agree")
        if not agree.is_selected():
            agree.click()
    except Exception:
        pass
    browser.find_element(By.CSS_SELECTOR, "input.btn-primary").click()
    WebDriverWait(browser, 10).until(lambda d: "route=account/success" in d.current_url)
    assert "route=account/success" in browser.current_url

def test_registration_existing_user(browser, base_url):
    """
    Цель: регистрация с уже существующим email -> ошибка.
    Ожидаемый результат: видим alert с информацией об уже зарегистрированном email.
    """
    # сначала создаём пользователя
    email = f"exist_{int(time.time())}@example.com"
    password = "Qq12345678"
    browser.get(base_url + "/index.php?route=account/register")
    wait = WebDriverWait(browser, 10)
    wait.until(EC.visibility_of_element_located((By.ID, "input-firstname"))).send_keys("Ivan")
    browser.find_element(By.ID, "input-lastname").send_keys("Petrov")
    browser.find_element(By.ID, "input-email").send_keys(email)
    browser.find_element(By.ID, "input-telephone").send_keys("1234567890")
    browser.find_element(By.ID, "input-password").send_keys(password)
    browser.find_element(By.ID, "input-confirm").send_keys(password)
    try:
        agree = browser.find_element(By.NAME, "agree")
        if not agree.is_selected():
            agree.click()
    except Exception:
        pass
    browser.find_element(By.CSS_SELECTOR, "input.btn-primary").click()
    WebDriverWait(browser, 10).until(lambda d: "route=account/success" in d.current_url)

    # пытаемся снова зарегистрировать этот же email
    browser.get(base_url + "/index.php?route=account/register")
    wait.until(EC.visibility_of_element_located((By.ID, "input-firstname"))).send_keys("Ivan")
    browser.find_element(By.ID, "input-lastname").send_keys("Petrov")
    browser.find_element(By.ID, "input-email").send_keys(email)
    browser.find_element(By.ID, "input-telephone").send_keys("1234567890")
    browser.find_element(By.ID, "input-password").send_keys(password)
    browser.find_element(By.ID, "input-confirm").send_keys(password)
    try:
        agree = browser.find_element(By.NAME, "agree")
        if not agree.is_selected():
            agree.click()
    except Exception:
        pass
    browser.find_element(By.CSS_SELECTOR, "input.btn-primary").click()
    alert = wait.until(EC.visibility_of_element_located((By.CSS_SELECTOR, ".alert-danger")))
    assert "зарегистрирован" in alert.text.lower() or "already registered" in alert.text.lower()
