class TransactionsController < ApplicationController
  layout "app"
  
  def index  
    @transable = find_transable  
    @transactions = @transable.transactions
  end
  
  def new
    @transable = find_transable 
    @coupon_prices = CouponPrice.all
    @transaction = Transaction.new
    @coupon_prices.each do |couponPrice|
      @transaction.tr_details.build
    end
      
  end
  
  def create  
    @transable = find_transable  
    @transaction = @transable.transactions.build
    @transaction.update_attributes( {'trdate'=>params[:transaction][:trdate], 'tramount'=>params[:transaction][:tramount] } )
    
    params[:transaction]['tr_detail_attributes'].each do |d|
      @transaction.tr_details.create(d)
    end
    #@transaction = @transable.transactions.build(params[:transaction])
    
    if @transaction.save  
      flash[:notice] = "Successfully saved Transaction."  
      redirect_to :action => 'index'
    else  
      render :action => 'new'  
    end  
  end
  
  
  # def tr_detail_attributes=(tr_detail_attributes)
  #   tr_detail_attributes.each do |attributes|
  #     transaction.tr_details.build( attributes )
  #   end
  # end
  
  
  def find_transable  
    params.each do |name, value|  
      if name =~ /(.+)_id$/  
        return $1.classify.constantize.find(value)  
      end  
    end  
    nil  
  end
  
end
