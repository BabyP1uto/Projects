from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

def test_login_valid(browser, new_user, base_url):
    """
    Цель: успешный вход (используем фикстуру new_user).
    Ожидаемый результат: редирект на страницу аккаунта.
    """
    email = new_user["email"]
    password = new_user["password"]
    browser.get(base_url + "/index.php?route=account/login")
    wait = WebDriverWait(browser, 10)
    wait.until(EC.visibility_of_element_located((By.ID, "input-email"))).send_keys(email)
    browser.find_element(By.ID, "input-password").send_keys(password)
    browser.find_element(By.CSS_SELECTOR, "input.btn-primary").click()
    WebDriverWait(browser, 10).until(lambda d: "route=account/account" in d.current_url)
    assert "route=account/account" in browser.current_url

def test_login_invalid(browser, base_url):
    """
    Цель: вход с некорректными данными -> ошибка.
    """
    browser.get(base_url + "/index.php?route=account/login")
    wait = WebDriverWait(browser, 10)
    wait.until(EC.visibility_of_element_located((By.ID, "input-email"))).send_keys("wrong@example.com")
    browser.find_element(By.ID, "input-password").send_keys("wrongpass")
    browser.find_element(By.CSS_SELECTOR, "input.btn-primary").click()
    alert = wait.until(EC.visibility_of_element_located((By.CSS_SELECTOR, ".alert-danger")))
    assert "неправ" in alert.text.lower() or "warning" in alert.text.lower()
