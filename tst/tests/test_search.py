from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

def test_search_valid(browser, base_url):
    """
    Цель: поиск существующего товара.
    Ожидаемый результат: список товаров не пуст.
    """
    browser.get(base_url)
    wait = WebDriverWait(browser, 10)
    search = wait.until(EC.visibility_of_element_located((By.NAME, "search")))
    search.clear()
    search.send_keys("iPhone")
    browser.find_element(By.CSS_SELECTOR, "button.btn-default").click()
    products = WebDriverWait(browser, 10).until(EC.presence_of_all_elements_located((By.CSS_SELECTOR, ".product-layout")))
    assert len(products) > 0

def test_search_no_results(browser, base_url):
    """
    Цель: поиск несуществующего товара.
    Ожидаемый результат: товаров не найдено (список пуст).
    """
    browser.get(base_url)
    wait = WebDriverWait(browser, 10)
    search = wait.until(EC.visibility_of_element_located((By.NAME, "search")))
    search.clear()
    search.send_keys("zxqwyu123_not_exists")
    browser.find_element(By.CSS_SELECTOR, "button.btn-default").click()
    product_elements = browser.find_elements(By.CSS_SELECTOR, ".product-layout")
    assert len(product_elements) == 0
