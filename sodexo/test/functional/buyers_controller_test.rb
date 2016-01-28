require 'test_helper'

class BuyersControllerTest < ActionController::TestCase
  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:buyers)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create buyer" do
    assert_difference('Buyer.count') do
      post :create, :buyer => { }
    end

    assert_redirected_to buyer_path(assigns(:buyer))
  end

  test "should show buyer" do
    get :show, :id => buyers(:one).to_param
    assert_response :success
  end

  test "should get edit" do
    get :edit, :id => buyers(:one).to_param
    assert_response :success
  end

  test "should update buyer" do
    put :update, :id => buyers(:one).to_param, :buyer => { }
    assert_redirected_to buyer_path(assigns(:buyer))
  end

  test "should destroy buyer" do
    assert_difference('Buyer.count', -1) do
      delete :destroy, :id => buyers(:one).to_param
    end

    assert_redirected_to buyers_path
  end
end
