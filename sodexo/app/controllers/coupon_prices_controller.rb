class CouponPricesController < ApplicationController
  layout "app"
  # GET /coupon_prices
  # GET /coupon_prices.xml
  def index
    @coupon_prices = CouponPrice.all

    respond_to do |format|
      format.html # index.html.erb
      format.xml  { render :xml => @coupon_prices }
    end
  end

  # GET /coupon_prices/1
  # GET /coupon_prices/1.xml
  def show
    @coupon_price = CouponPrice.find(params[:id])

    respond_to do |format|
      format.html # show.html.erb
      format.xml  { render :xml => @coupon_price }
    end
  end

  # GET /coupon_prices/new
  # GET /coupon_prices/new.xml
  def new
    @coupon_price = CouponPrice.new

    respond_to do |format|
      format.html # new.html.erb
      format.xml  { render :xml => @coupon_price }
    end
  end

  # GET /coupon_prices/1/edit
  def edit
    @coupon_price = CouponPrice.find(params[:id])
  end

  # POST /coupon_prices
  # POST /coupon_prices.xml
  def create
    @coupon_price = CouponPrice.new(params[:coupon_price])

    respond_to do |format|
      if @coupon_price.save
        format.html { redirect_to(@coupon_price, :notice => 'CouponPrice was successfully created.') }
        format.xml  { render :xml => @coupon_price, :status => :created, :location => @coupon_price }
      else
        format.html { render :action => "new" }
        format.xml  { render :xml => @coupon_price.errors, :status => :unprocessable_entity }
      end
    end
  end

  # PUT /coupon_prices/1
  # PUT /coupon_prices/1.xml
  def update
    @coupon_price = CouponPrice.find(params[:id])

    respond_to do |format|
      if @coupon_price.update_attributes(params[:coupon_price])
        format.html { redirect_to(@coupon_price, :notice => 'CouponPrice was successfully updated.') }
        format.xml  { head :ok }
      else
        format.html { render :action => "edit" }
        format.xml  { render :xml => @coupon_price.errors, :status => :unprocessable_entity }
      end
    end
  end

  # DELETE /coupon_prices/1
  # DELETE /coupon_prices/1.xml
  def destroy
    @coupon_price = CouponPrice.find(params[:id])
    @coupon_price.destroy

    respond_to do |format|
      format.html { redirect_to(coupon_prices_url) }
      format.xml  { head :ok }
    end
  end
end
