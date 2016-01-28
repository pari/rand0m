class Returncheck < ActiveRecord::Base
  belongs_to :paycheck
  
  def commenticon 
    if self.comment.to_s.strip != ''
      return "<img src='/images/comments_yes.png' title='"+self.comment+"'>"
    else
      return "<img src='/images/comments_no.png'>"
    end
  end
  
  
end
