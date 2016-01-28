class Paycheck < ActiveRecord::Base
  belongs_to :employee
  has_many :returnchecks, :dependent => :destroy
  validates_uniqueness_of :employee_id, :scope => [:payperiod_startdate, :payperiod_enddate], :message => 'An entry for this employee in this timeperiod is already entered !'
  validates_presence_of :employee_id , :payperiod_startdate , :payperiod_enddate, :paydate , :payamount
  
  
  def payinamount
    payinamount = 0
    self.returnchecks.each do |rc|
      payinamount = payinamount + rc.amount
    end
    return payinamount
  end
  
  
  
  
  def payinamount_htmlcolor
    if self.payinamount == self.payamount
      return "<span style='color:green;'>#{payinamount}</span>"
    else
      return "<span style='color:red;'>#{payinamount}</span>"
    end
  end
  
  
  
  
  
  
  def addReturnCheckLink
    if self.payinamount == self.payamount
      return "&nbsp;"
    else
      return "<span style='color:red;'>#{payinamount}</span>"
    end
  end
  
  
  
  
end
