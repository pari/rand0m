require 'test_helper'

class CouponPricesControllerTest < ActionController::TestCase
  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:coupon_prices)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create coupon_price" do
    assert_difference('CouponPrice.count') do
      post :create, :coupon_price => { }
    end

    assert_redirected_to coupon_price_path(assigns(:coupon_price))
  end

  test "should show coupon_price" do
    get :show, :id => coupon_prices(:one).to_param
    assert_response :success
  end

  test "should get edit" do
    get :edit, :id => coupon_prices(:one).to_param
    assert_response :success
  end

  test "should update coupon_price" do
    put :update, :id => coupon_prices(:one).to_param, :coupon_price => { }
    assert_redirected_to coupon_price_path(assigns(:coupon_price))
  end

  test "should destroy coupon_price" do
    assert_difference('CouponPrice.count', -1) do
      delete :destroy, :id => coupon_prices(:one).to_param
    end

    assert_redirected_to coupon_prices_path
  end
end
